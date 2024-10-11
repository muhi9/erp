<?php
namespace BaseBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

/**
 * @author Anton Blajev <anton@valqk.com>
 *
 * Usage:
 * use BaseBundle\Form\Type\TreeChoiceType;
 *
 * ->add('categories', TreeChoiceType::class, array(
 *      'class' => 'BaseBundle:NomType', (optional - points to NomType)
 *      'multiple' => false,
 *      'required' => true,
 *      'label' => 'entity.news.categories',
 *  ))
 */

            /*
            to load preset tree, you must set BaseNomLoad like this:
            $(function() {
            treeSettings['showTree'] = false;
              treeSettings['disableSelected'] = true;
            selectBnomType($('#BaseBundle_basenoms_type'))
                  BaseNomLoad['course.issue'] = {"name":"Course Issue","id":611,"value":"Issue 1","type":"course.issue"};
              BaseNomLoad['course.course'] = {"name":"Course Name","id":613,"value":"PPL","type":"course.course"};
              BaseNomLoad['course.subCourse'] = {"name":"Sub Course Name","id":614,"value":"A","type":"course.subCourse"};
              BaseNomLoad['course.part'] = {"name":"Course part","id":615,"value":"Theoretical","type":"course.part"};
              BaseNomLoad['course.subjectPhase'] = {"name":"Course Phase\/Theory subject","id":616,"value":"AGK - Airframe, Systems and Power Plant","type":"course.subjectPhase"};
              BaseNomLoad['course.lessonExercise'] = {"name":"Lesson\/Exercise","id":617,"value":"Airframe","type":"course.lessonExercise"};
              BaseNomLoad['course.topic'] = {"name":"Course topic","id":618,"value":"Materials","type":"course.topic"};
              BaseNomLoad['course.subTopic'] = {"name":"Course subtopic","id":619,"value":"Composite and other materials","type":"course.subTopic"};
              BaseNomLoad['course.element'] = {"name":"Course element","id":621,"value":"Principle of a composite material","type":"course.element"};
          })
          you can do it with code like:
                  if($basenom->getParentId()!=null){
            $basenomParen = $basenom->getParentId();
            while ($basenomParen!=null) {
                $parent = $this->getDoctrine()->getRepository(BaseNoms::class)->find($basenomParen);
                $parentName = $this->getDoctrine()->getRepository(NomType::class)->find($parent->getType());
                $parentsTypes[$parent->getType()]['id'] = $parent->getId();
                $parentsTypes[$parent->getType()]['name'] = $parentName->getName();
                $parentsTypes[$parent->getType()]['value'] = $parent->getName();
                $parentsTypes[$parent->getType()]['type'] = $parent->getType();

                $basenomParen = $parent->getParentId();
            }

        }

        $formProps['action'] = $this->generateUrl($request->get('_route'));
        //print_r($parentsTypes);exit;
        if ($parentsTypes) {
            $formProps['parent']=$parentsTypes;
            $data['baseNomLoad'] = $parentsTypes;
{% block content %}
{#{dump(form.buildDate)}#}
{{ form_start(form) }}
{{ form_widget(form) }}
{{ form_end(form) }}
{#% if reloadType == true %#}
<script type="text/javascript">
$(function() {
  {% if treeSettings is defined %}
  {% for key, val in treeSettings %}
    {% if val in [true,false] %}
    treeSettings['{{key}}'] = {{val}};
    {% else %}
    treeSettings['{{key}}'] = '{{val}}';
    {% endif %}
  {% endfor %}
  {% endif %}
    selectBnomType($('#BaseBundle_basenoms_type'))
    {% if baseNomLoad is defined %}
      {% for key,val in baseNomLoad %}
        BaseNomLoad['{{key}}'] = {{val|json_encode()|raw}};
      {% endfor %}
    {% endif %}
})
</script>
{#% endif %#}

{% endblock %}

{% block javascripts %}

{% javascripts filter='uglifyjs2'
    '%kernel.root_dir%/../web/assets/vendors/custom/datatables/datatables.bundle.js'
    output='js/0_load_datatables.js'
%}
<script type="text/javascript" src="{{ asset_url }}"></script>
{% javascripts filter='uglifyjs2' "@BaseBundle/Resources/public/js/basenom.js"%}
        <script src="{{ asset_url }}" type="text/javascript"></script>
{% endjavascripts %}


{% endjavascripts %}
{% endblock %}

          */
class TreeChoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $choices = [];
        //print_r($view->vars['data']);exit;
        //dump($options['preloadParents']);exit;
        //dump($view->vars['choices']);
        $view->vars['preloadParents'] = $options['preloadParents'];
        $view->vars['treeSettings'] = $options['treeSettings'];
        return;
        foreach ($view->vars['choices'] as $choice) {
             //dump('out loop');
             $pkk = $choice->data->getParentNameKey();
             if($pkk!=null){
                $ch=[];
                $ch[] = $choice->data;
                while($pkk != null) {
                    //dump('in loop');
                    //dump($pkk);
                    if ($pkk->getParentNameKey() != null){
                         $ch[] = $pkk;
                         $pkk = $pkk->getParentNameKey();
                    }else{
                        $pkk=null;
                    }
                }

                $keys = array_reverse($ch);
                $i=0;

                foreach ($keys as $value) {
                    $i++;
                    $choices[] = $this->buildTreeChoices($value,$i);
                }

             }else{
                $choices[] = $this->buildTreeChoices($choice->data);
             }
        }
        $view->vars['choices'] = $choices;

    }

     protected function buildTreeChoices($choice, $level = 0){
        $result = array();
            $result = new ChoiceView(
                $choice,
                (string)$choice->getId(),
                str_repeat('--', $level) . ' ' . $choice->getName(),
                []
            );


        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'preloadParents' => null,
          'treeSettings' => [
            'showTree' => 'true', // hides the tree in accordeon
            'disableSelected' => 'false', // if true, all tree nom that are selected are disabled
            'hideLastEl' => 'false', // hides last tree element - the one edited at the moment.
            'lastElAsSelect' => 'false', // shows last element as select
            'lastTreeElement' => null, // define LAST tree element. If key of the basenom = this string js won't add more els.
          ],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
