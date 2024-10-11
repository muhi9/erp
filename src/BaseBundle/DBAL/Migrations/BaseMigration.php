<?php

namespace BaseBundle\DBAL\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

abstract class BaseMigration extends AbstractMigration
{
    /**
    * Add/Update records in db.
    * $update var in format:
    [
        'table'=>'table',
        'pk_name'=>'fld1',
        'data'=>[
            ['fld1'=>'val','fld2'=>'val',...],
            ['fld1'=>'val1','fld2'=>'val1',...],
        ],
    ]
    */
    public function addUpdate($update) {
        foreach($update['data'] as $up) {
            if (isset($up['extra_field']) && !empty($up['extra_field'])) {
                $up['extra_field'] = json_encode($up['extra_field']);
            }
            $force = null;
            if (isset($up['force'])) {
                $force = (in_array($up['force'], ['insert','update'])?$up['force']:null);
                unset($up['force']);
            }

            // check for select statement in value and if any - exec and set as value
            foreach ($up as $fld => $val) {
                if (stristr($val, 'select') && stristr($val, 'from')) {
                    $up[$fld] = $this->connection->fetchColumn($val);
                }
            }

            $data = $up;
            $upWhere = [];
            $countWhere = $countWhereVals = null;
            if (is_array($update['pk_name'])) {
                $countWhere = '';
                $countWhereVals = [];
                foreach ($update['pk_name'] as $fld) {
                    $countWhere = $fld . ' = :'.$fld . ' AND ';
                    $upWhere[$fld] = $up[$fld];
                    $countWhereVals[$fld] = $up[$fld];
                    unset($data[$fld]);
                }
                $countWhere = substr($countWhere, 0, -5);
            } else {
                if (isset($up[$update['pk_name']]) && !empty($up[$update['pk_name']])) {
                    $countWhere = $update['pk_name'].' = :'.$update['pk_name'];
                    $countWhereVals = [$update['pk_name'] => $up[$update['pk_name']]];
                    $upWhere[$update['pk_name']] = $up[$update['pk_name']];
                }
            }
            if ($countWhere) {
                $countSql = 'SELECT count(*) FROM ' . $update['table'] . ' where ' . $countWhere;
                $count = $this->connection->fetchColumn($countSql, $countWhereVals, 0);
            } else {
                $count = 0;
            }
            //echo "------------\n".$countSql."\n---------------------\n";
            if ($count == 0 || $force == 'insert') {
                // insert
                $this->connection->insert($update['table'],$up);
            }
            else if ($count > 0 || $force == 'update') {
                //update
                $this->connection->update($update['table'], $data, $upWhere);
            }
        }

    }

    public function addSqls(array $sqls) {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        foreach($sqls as $sql)
            $this->addSql($sql);
    }


    protected function deleteNomType($nom_type) {
        $this->addSqls([
            "DELETE FROM base_noms_extra WHERE basenom_id IN (SELECT id FROM base_noms WHERE type LIKE '$nom_type')",
            "DELETE FROM base_noms WHERE type LIKE '$nom_type' ORDER BY parent_id DESC",
            "DELETE FROM nom_type WHERE name_key LIKE '$nom_type' ORDER BY parent_name_key DESC",
        ]);
    }






    /**
     * &$node = [
     *     name: Name of node
     *     type: nom_type value
     *     [parent]: IF NUMERIC (use as parent_id) ELSE IF ARRAY (find parent_id by array) ELSE (throw exeption)
     *     [status=1]: Enabled
     *     [bnorder=auto]: Order for output
     *     [extra]: Array of extra fields (base_noms_extra)
     *     [id]: IF EMPTY (Replace element by ID) ELSE (Replace element by NAME and TYPE)
     *     [sub]: Array of sub nodes
     * ];
     */
    protected function writeBaseNomsTree(&$node, $order = 0, $path_types = [], $path_names = []) {
        if ((empty($node['name']) && $node['name']!=0) || empty($node['type'])) {
            throw new \Exception("Invalid node name or type\n".print_r($node, 1), 1);
        }
        $node['name'] = trim($node['name']);
        if (is_array($node['type'])) {
            if (empty($node['type']['name_key'])) {
                throw new Exception("name_key is missing (nom_type: ".print_r($type, 1).")", 1);
            }
            $node_type = trim($node['type']['name_key']);
        } else {
            $node_type = trim($node['type']);
        }

        // parent node
        if (array_key_exists('parent', $node)) {
            if (is_null($node['parent'])) {
                $parent_id = $node['parent'];
            } else if (is_numeric($node['parent'])) {
                $parent_id = $node['parent'];
            } else if (is_array($node['parent'])) {
                $parent_id = $this->connection->fetchColumn('SELECT id FROM base_noms WHERE '.implode('=? AND ', array_keys($node['parent'])).'=?', array_values($node['parent']), 0);
            }
        } else if ($order==0 && empty($path_types) && empty($path_names)) { // root node
            $parent_id = null;
        }
        if (empty($parent_id) && !is_null($parent_id)) {
            throw new \Exception("Unknown parent_id for node $node[name] ($node_type)", 1);
        }
        $node['parent_id'] = $parent_id;

        // extra fields
        $extra = empty($node['extra']) ? NULL : $node['extra'];

        // update by (id) or (name+type)
        if (array_key_exists('id', $node)) {
            if (is_array($node['id'])) {
                $value = $this->connection->fetchAssoc("SELECT * FROM base_noms WHERE name=? AND type=?", [$node['name'], $node_type]);
            } else {
                $value = $this->connection->fetchAssoc("SELECT * FROM base_noms WHERE id=?", [$node['id']]);
            }
            if (empty($value)) {
                throw new \Exception("Not found node with provided id ".(empty($node['id']) ? "$node[name] ($node_type)" : $node['id']), 1);
            }
            $value['updated_at'] = date('Y-m-d H:i:s');
            // parent nom_type elements
            $counter = 10;
            $path_types = [];
            $path_names = [];
            $parent_name_key1 = $value['type'];
            do {
                $parent_nom_type = $this->connection->fetchAssoc("SELECT * FROM nom_type WHERE name_key=?", [$parent_name_key1]);
                if (empty($parent_nom_type)) {
                    throw new \Exception("Not found note type '$value[type]' ".(empty($node['id']) ? "$node[name] ($node_type)" : $node['id']), 1);
                } else {
                    $path_names[] = $parent_nom_type['name'];
                    $path_types[] = $parent_nom_type['parent_name_key1'];
                    $parent_name_key1 = $parent_nom_type['parent_name_key1'];
                }
            } while ($counter-- && $parent_nom_type && $parent_name_key1);
            $path_names = array_reverse($path_names);
            $path_types = array_reverse($path_types);
        } else {
            $value = [
                'name' => $node['name'],
                'type' => $this->nom_type($node['type'], $node['name'], $path_types, $path_names, $extra),
                'bnorder' => isset($node['bnorder']) ? $node['bnorder'] : $order,
                'parent_id' => $parent_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '0000-00-00 00:00:00',
            ];
        }

        // update values
        $value['status'] = isset($node['status']) ? ($node['status'] ? 1 : 0) : 1;
        $value['bnom_key'] = isset($node['bnom_key']) ? $node['bnom_key'] : (isset($value['bnom_key']) ? $value['bnom_key'] : NULL);
$temptemptemp = [];
foreach ($value as $key=>$val) {
    $temptemptemp["`$key`"] = $val;
}

        // write
        if (isset($value['id'])) {
            $this->connection->update('base_noms', $temptemptemp, ['id'=>$value['id']]);
            $node['id'] = $value['id'];
        } else {
            $this->connection->insert('base_noms', $temptemptemp);
            $node['id'] = $this->connection->lastInsertId();
        }

        // write extra fields
        if (!empty($extra)) {
            foreach ($extra as $key=>$val) {
                $id = $this->connection->fetchColumn('SELECT id FROM base_noms_extra WHERE basenom_id=? AND baseKey=?', [$node['id'], $key], 0);
                if ($id) {
                    $temp = [
                        'baseValue' => $val,
                        'basenom_id' => $node['id'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->connection->update('base_noms_extra', $temp, ['id'=>$id]);
                } else {
                    $temp = [
                        'baseKey' => $key,
                        'baseValue' => $val,
                        'basenom_id' => $node['id'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => '0000-00-00 00:00:00',
                    ];
                    $this->connection->insert('base_noms_extra', $temp);
                }
            }
        }

        // sub elements
        if (!empty($node['sub'])) {
            $i = $this->connection->fetchColumn('SELECT MAX(bnorder) FROM base_noms WHERE parent_id=?', [$node['id']], 0) ?: 0;
            $path_types[] = $node_type;
            $path_names[] = is_array($node['type']) && !empty($node['type']['name']) ? trim($node['type']['name']) : $node['name'];
            foreach ($node['sub'] as &$sub) {
                if (empty($sub['parent'])) {
                    $sub['parent'] = $node['id'];
                }
                $this->writeBaseNomsTree($sub, $i++, $path_types, $path_names);
            }
        }
    }



    protected function unfoldBaseNomsTree(&$node, $unfoldKey = 'name', $unfoldSeparator = '//', $path = []) {
        $result = [];
        $result[ltrim(implode($unfoldSeparator, $path) . $unfoldSeparator . $node[$unfoldKey], $unfoldSeparator)] = &$node;
        if (!empty($node['sub'])) {
            $path[] = $node[$unfoldKey];
            foreach ($node['sub'] as &$sub) {
                $result += $this->unfoldBaseNomsTree($sub, $unfoldKey, $unfoldSeparator, $path);
            }
        }
        return $result;
    }



    protected function deleteBaseNomsTree($node, $delete_empty_nom_types = true) {
        if (empty($node['name']) || empty($node['type'])) {
            throw new \Exception("Invalid node name or type", 1);
        }
        $node['name'] = trim($node['name']);
        $node['type'] = trim($node['type']);

        // find ID
        $parent_id = NULL;
        if (is_numeric($node['parent'])) {
            $parent_id = $node['parent'];
        } else if (is_array($node['parent'])) {
            $parent_id = $this->connection->fetchColumn('SELECT id FROM base_noms WHERE '.implode('=? AND ', array_keys($node['parent'])).'=?', array_values($node['parent']), 0);
        }
        $id = $this->connection->fetchColumn("SELECT * FROM base_noms WHERE name=? AND type=? AND parent_id=?", [$node['name'], $node['type'], $parent_id], 0);

        // sub elements
        if (!empty($node['sub'])) {
            foreach ($node['sub'] as &$sub) {
                if (empty($sub['parent'])) {
                    $sub['parent'] = $id;
                }
                $this->deleteBaseNomsTree($sub, $delete_empty_nom_types);
            }
        }

        // delete created nodes
        if (!array_key_exists('id', $node)) {
            $this->connection->delete('base_noms', ['id'=>$id]);
            $this->connection->delete('base_noms_extra', ['basenom_id'=>$id]);
        }

        if ($delete_empty_nom_types) {
            $temp = $this->connection->fetchColumn('SELECT COUNT(*) FROM base_noms WHERE type=?', [$node['type']], 0);
            if (!$temp) {
                $temp = $this->connection->fetchAssoc("SELECT * FROM nom_type WHERE name_key=?", [$node['type']]);
                $temp = $temp['parent_name_key'].'=>'.$temp['name'];
                $temp = $this->connection->fetchColumn("SELECT COUNT(*) FROM nom_type WHERE parent_name_key LIKE '?%'", [$temp], 0);
                if (!$temp) {
                    $this->connection->delete('nom_type', ['name_key'=>$node['type']]);
                }
            }
        }
    }


    private function nom_type($type, $name, $path_types, $path_names, $extra) {
        static $nom_type_cache = []; // [path / name_key=>db row]

        $extra = empty($extra) ? [] : array_keys($extra);

        if (is_array($type)) {
            $descr = array_key_exists('descr', $type) ? $type['descr'] : null;
            $extra = array_key_exists('extra_field', $type) 
                ? array_values(array_unique(array_merge($extra, $type['extra_field'])))
                : $extra;
            $name = empty($type['name']) ? $name : $type['name'];
            $type = $type['name_key'];
        }
        $descr = isset($descr) ? $descr : (empty($path_names) ? $name : ltrim(implode(' / ', $path_names).' / '.$name, '/'));

        if (empty($nom_type_cache[$type])) {
            $nom_type_cache[$type] = $this->connection->fetchAssoc("SELECT * FROM nom_type WHERE name_key=?", [$type]);
        }

        if (empty($nom_type_cache[$type])) {
            $temp = [
                'name_key' => $type,
                'name' => $name,
                'parent_name_key' => implode('=>', $path_types),
                'parent_name_key1' => array_pop($path_types),
                'status' => 1,
                'descr' => $descr,
                'extra_field' => $extra ? json_encode($extra) : NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '0000-00-00 00:00:00',
            ];
            if ($this->connection->insert('nom_type', $temp)) {
                $nom_type_cache[$type] = $temp;
            } else {
                throw new \Exception("Error Processing Request", 1);
                return NULL;
            }
        } else if (count($extra)>count($nom_type_cache[$type]['extra_field'])) {
            $temp = $this->connection->fetchColumn('SELECT extra_field FROM nom_type WHERE name_key=?', [$type], 0);
            if (!empty($temp)) {
                $extra = array_values(array_unique(array_merge(json_decode($temp, true), $extra ?: [])));
            }
            $this->connection->update('nom_type', ['extra_field'=>json_encode($extra)], ['name_key' => $type]);
        }
        $nom_type_cache[$type]['extra_field'] = $extra ? $extra : NULL;
        return $type;
    }
}
