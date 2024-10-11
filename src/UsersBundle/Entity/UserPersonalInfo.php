<?php

namespace UsersBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use BaseBundle\Traits\FileEntity;

/**
 * UserPersonalInfo
 *
 * @ORM\Table(name="user_personal_info", indexes={
 *              @Index(columns={"person_type_id"}),
 *          })
 * @ORM\Entity(repositoryClass="UsersBundle\Repository\UserPersonalInfoRepository")
 * @UniqueEntity(
 *     fields={"nickname"},
 *     errorPath="nickname",
 *     message="This nickname is already taken."
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class UserPersonalInfo
{
    use BlameStampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\UserPersonalInfo")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parentOrganisation;

    /**
     * @ORM\OneToOne(targetEntity="UsersBundle\Entity\User", inversedBy="info", orphanRemoval=true, cascade={"persist"}))
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     * @Assert\Valid()
     */
    private $user = null;

    /**
     * @ORM\Column(name="message_provider", type="json_array", nullable=true)
     */
    private $message_provider;


    /**
     * @ORM\Column(name="dashboard_settings", type="json_array", nullable=true)
     */
    private $dashboard_settings;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $disabled = false;

    /**
     * @var int
     *
     * @ORM\Column(type="decimal", precision=25, scale=4, nullable=true)
     */
    private $credit;

    /**
     * @var int
     *
     * @ORM\Column(type="integer",  nullable=true)
     */
    private $delayed_payment;

//////////////////////////////////////////////////////////////////////////////////////////////////// PERSON
    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $personType;

    /**
     * @ORM\ManyToMany(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinTable(name="user_profile_role_types",
     *      joinColumns={@ORM\JoinColumn(name="profile_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="basenom_id", referencedColumnName="id")}
     *      )
    */
    private $personSubType;
  
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(max = 70)
     */
    private $firstName;

  
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(max = 70)
     */
    private $middleName;

  
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(max = 70)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(max = 70)
     */
    private $nickname;
   
    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\Country")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $nationality;

    /**
     * @ORM\Column(name="languages", type="array", nullable=true)
     */
    private $languages;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $company;

   



//////////////////////////////////////////////////////////////////////////////////////////////////// LEGAL PERSON
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $companyName;



   /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinColumn(name="company_type_id", referencedColumnName="id")
     */
    private $companyType;


    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $companyID;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $companyVAT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyPerson;

   

//////////////////////////////////////////////////////////////////////////////////////////////////// CONTACTS
    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="personalInfo", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="personalInfo", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="personalInfo", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="personalInfo", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $soc;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="personalInfo", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $im;


    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserAddress", mappedBy="personalInfo", orphanRemoval=true, cascade={"persist"}))
     * @Assert\Valid()
     */
    private $addresse;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="personalInfo", orphanRemoval=true, cascade={"persist"}))
     * @Assert\Valid()
     */
    private $call_sing;


//////////////////////////////////////////////////////////////////////////////////////////////////// BANK ACCOUNT
    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserBankAccount", mappedBy="personalInfo", orphanRemoval=true, cascade={"persist"}))
     * @Assert\Valid()
     */
    private $bank;


//////////////////////////////////////////////////////////////////////////////////////////////////// MISC
    /**
     * @ORM\ManyToMany(targetEntity="FileBundle\Entity\File", cascade={"persist","remove"},orphanRemoval=true)
     * @ORM\JoinTable(name="user_images",
     *      joinColumns={@ORM\JoinColumn(name="personal_info_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     * @Assert\Valid()
     */
    private $images;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param $user
     *
     * @return UserPersonalInfo
     */
    public function setUser($user)
    {
        //print_r($user);
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set language
     *
     * @param integer $language
     *
     * @return UserPersonalInfo
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return UserPersonalInfo
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

   
    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return UserPersonalInfo
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
   
    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return UserPersonalInfo
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

   
   
    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return UserPersonalInfo
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

   
    /**
     * Set languages
     *
     * @param array $languages
     *
     * @return UserPersonalInfo
     */
    public function setLanguages($languages)
    {

        $this->languages = $languages;

        return $this;
    }

    /**
     * Get languages
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return UserPersonalInfo
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }
   
     /**
     * Set message_provider
     *
     * @param \json $message_provider
     *
     * @return UserPersonalInfo
     */
    public function setMessageProvider($message_provider)
    {
       // $this->message_provider = $message_provider;
        $this->message_provider = json_encode($message_provider);
        return $this;
    }

    /**
     * Get message_provider
     *
     * @return \json
     */
    public function getMessageProvider()
    {
        if(is_string($this->message_provider)){
            return json_decode($this->message_provider,true,512,JSON_OBJECT_AS_ARRAY);
        }else{
            return $this->message_provider;
        }

    }

    public function getActiveMessageProvider($providerId=false){
        $result = [];
        $mp = $this->getMessageProvider();

        if(!empty($mp)){
            foreach ($mp as $key => $provider) {
                if(!empty($provider['active'])){
                    $result[$key] = $provider;
                }
            }

        }
        return !empty($providerId)?$result[$providerId]:$result;
    }

    public function getFullName($official = false)
    {
        $name = '---';
        if($this->getPersonType() != NULL) {
            if ($official) {
                if($this->getPersonType()->getBnomKey()=='company') {
                    $name = $this->getCompanyName() . $this->getCompanyType()->getName();
                } else {
                    $name = $this->getFirstName() . ' '
                            . $this->getLastName();
                }
            } else {
                if ($this->getPersonType()->getBnomKey()=='company') {
                    if (!empty($this->getNickname())) {
                        $name = $this->getNickname();
                    }else {
                        $name = $this->getCompanyName() .' '
                            . ($this->getCompanyType()->getName()?$this->getCompanyType()->getName():' (LP) ');
                    }
                } else {
                    $name = '^';
                    if ($this->getFirstName()) {
                        $name = $this->getFirstName() . ' ' . $this->getLastName();
                    } else {
                        $name = $this->getFirstName() . ' '
                                . $this->getLastName();
                    }
                    if (!empty($this->getNickname()))
                        $name .= ' (' . $this->getNickname() . ')';
                }
            }
        }
        
        return $name;
    }


    public function getFullNameSpan($official = false) {
        return '<span'.($this->getDisabled() ? ' class="deleted-item"' : '').'>'.$this->getFullName($official).'</span>';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->phone = new ArrayCollection();
        $this->mail = new ArrayCollection();
        $this->url = new ArrayCollection();
        $this->soc = new ArrayCollection();
        $this->im = new ArrayCollection();
        $this->emergency = new ArrayCollection();
        $this->call_sing = new ArrayCollection();
        $this->address = new ArrayCollection();
        $this->document = new ArrayCollection();
        $this->bank = new ArrayCollection();
        $this->images = new ArrayCollection();
    }



    /**
     * Set parentOrganisation.
     *
     * @param \UsersBundle\Entity\UserPersonalInfo|null $parentOrganisation
     *
     * @return UserPersonalInfo
     */
    public function setParentOrganisation(\UsersBundle\Entity\UserPersonalInfo $parentOrganisation = null)
    {
        $this->parentOrganisation = $parentOrganisation;

        return $this;
    }

    /**
     * Get parentOrganisation.
     *
     * @return \UsersBundle\Entity\UserPersonalInfo|null
     */
    public function getParentOrganisation()
    {
        return $this->parentOrganisation;
    }

    /**
     * Set nationality.
     *
     * @param \BaseBundle\Entity\Country|null $nationality
     *
     * @return UserPersonalInfo
     */
    public function setNationality(\BaseBundle\Entity\Country $nationality = null)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality.
     *
     * @return \BaseBundle\Entity\Country|null
     */
    public function getNationality()
    {
        return $this->nationality;
    }

  

    /**
     * Add phone.
     *
     * @param \UsersBundle\Entity\UserContact $phone
     *
     * @return UserPersonalInfo
     */
    public function addPhone(\UsersBundle\Entity\UserContact $phone)
    {
        $pi = $phone->getPersonalInfo();
        if (!$pi instanceof \UsersBundle\Entity\UserPersonalInfo)
            $phone->setPersonalInfo($this);
        $this->phone[] = $phone;

        return $this;
    }

    /**
     * Remove phone.
     *
     * @param \UsersBundle\Entity\UserContact $phone
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePhone(\UsersBundle\Entity\UserContact $phone)
    {
        return $this->phone->removeElement($phone);
    }

    /**
     * Get phone.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhone()
    {
        return $this->phone->filter(function($contact) {
            return $contact->getContactType() && $contact->getContactType()->getType()->getNameKey()=='user.phone';
        });
    }

    /**
     * Add mail.
     *
     * @param \UsersBundle\Entity\UserContact $mail
     *
     * @return UserPersonalInfo
     */
    public function addMail(\UsersBundle\Entity\UserContact $mail)
    {
        $pi = $mail->getPersonalInfo();
        if (!$pi instanceof \UsersBundle\Entity\UserPersonalInfo)
            $mail->setPersonalInfo($this);
        $this->mail[] = $mail;

        return $this;
    }

    /**
     * Remove mail.
     *
     * @param \UsersBundle\Entity\UserContact $mail
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMail(\UsersBundle\Entity\UserContact $mail)
    {
        return $this->mail->removeElement($mail);
    }

    /**
     * Get mail.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMail()
    {
        return $this->mail->filter(function($contact) {
            return $contact->getContactType() && $contact->getContactType()->getType()->getNameKey()=='user.mail';
        });
    }

    /**
     * Add url.
     *
     * @param \UsersBundle\Entity\UserContact $url
     *
     * @return UserPersonalInfo
     */
    public function addUrl(\UsersBundle\Entity\UserContact $url)
    {
        $pi = $url->getPersonalInfo();
        if (!$pi instanceof \UsersBundle\Entity\UserPersonalInfo)
            $url->setPersonalInfo($this);
        $this->url[] = $url;

        return $this;
    }

    /**
     * Remove url.
     *
     * @param \UsersBundle\Entity\UserContact $url
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUrl(\UsersBundle\Entity\UserContact $url)
    {
        return $this->url->removeElement($url);
    }

    /**
     * Get url.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUrl()
    {
        return $this->url->filter(function($contact) {
            return $contact->getContactType() && $contact->getContactType()->getType()->getNameKey()=='user.url';
        });
    }

    /**
     * Add soc.
     *
     * @param \UsersBundle\Entity\UserContact $soc
     *
     * @return UserPersonalInfo
     */
    public function addSoc(\UsersBundle\Entity\UserContact $soc)
    {
        $pi = $soc->getPersonalInfo();
        if (!$pi instanceof \UsersBundle\Entity\UserPersonalInfo)
            $soc->setPersonalInfo($this);
        $this->soc[] = $soc;

        return $this;
    }

    /**
     * Remove sox.
     *
     * @param \UsersBundle\Entity\UserContact $sox
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSoc(\UsersBundle\Entity\UserContact $sox)
    {
        return $this->sox->removeElement($sox);
    }

    /**
     * Get soc.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoc()
    {
        return $this->soc->filter(function($contact) {
            return $contact->getContactType() && $contact->getContactType()->getType()->getNameKey()=='user.soc';
        });
    }

    /**
     * Add address.
     *
     * @param \UsersBundle\Entity\UserAddress $address
     *
     * @return UserPersonalInfo
     */
    public function addAddresse(\UsersBundle\Entity\UserAddress $addresse)
    {
        $pi = $addresse->getPersonalInfo();
        if (!$pi instanceof \UsersBundle\Entity\UserPersonalInfo)
            $addresse->setPersonalInfo($this);
        $this->addresse[] = $addresse;

        return $this;
    }

    /**
     * Remove address.
     *
     * @param \UsersBundle\Entity\UserAddress $address
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAddresse(\UsersBundle\Entity\UserAddress $addresse)
    {
        return $this->addresse->removeElement($addresse);
    }

    /**
     * Get address.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresse()
    {
        return $this->addresse;
    }

    public function getAddresseArray()
    {
        $addresse = [];

        foreach ($this->addresse->getValues() as $key => $value) {
            $addresse[strtolower($value->getContactType()->getName())] = $value;
        }

        return $addresse;
    }

    /**
     * Add call_sing.
     *
     * @param \UsersBundle\Entity\UserContact $call_sing
     *
     * @return UserPersonalInfo
     */
    public function addCallSing(\UsersBundle\Entity\UserContact $call_sing)
    {
        $pi = $call_sing->getPersonalInfo();
        if (!$pi instanceof \UsersBundle\Entity\UserPersonalInfo)
            $call_sing->setPersonalInfo($this);
        $this->call_sing[] = $call_sing;

        return $this;
    }

    /**
     * Remove call_sing.
     *
     * @param \UsersBundle\Entity\UserContact $call_sing
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCallSing(\UsersBundle\Entity\UserContact $call_sing)
    {
        return $this->call_sing->removeElement($call_sing);
    }

    /**
     * Get call_sing.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCallSing()
    {

        return $this->call_sing->filter(function($contact) {
            return $contact->getContactType() && $contact->getContactType()->getType()->getNameKey()=='user.company_address';
        });
    }



    /**
     * Add document.
     *
     * @param \UsersBundle\Entity\UserIDDocument $document
     *
     * @return UserPersonalInfo
     */
    public function addDocument(\UsersBundle\Entity\UserIDDocument $document)
    {
        $pi = $document->getPersonalInfo();
        if (!$pi instanceof \UsersBundle\Entity\UserPersonalInfo)
            $document->setPersonalInfo($this);
        $this->document[] = $document;

        return $this;
    }

    /**
     * Remove document.
     *
     * @param \UsersBundle\Entity\UserIDDocument $document
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDocument(\UsersBundle\Entity\UserIDDocument $document)
    {
        return $this->document->removeElement($document);
    }

    /**
     * Get document.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocument()
    {
        return $this->document;
    }



    /**
     * Add bank.
     *
     * @param \UsersBundle\Entity\UserBankAccount $bank
     *
     * @return UserPersonalInfo
     */
    public function addBank(\UsersBundle\Entity\UserBankAccount $bank)
    {
        $pi = $bank->getPersonalInfo();
        if (!$pi instanceof \UsersBundle\Entity\UserPersonalInfo)
            $bank->setPersonalInfo($this);
        $this->bank[] = $bank;

        return $this;
    }

    /**
     * Remove bank.
     *
     * @param \UsersBundle\Entity\UserBankAccount $bank
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBank(\UsersBundle\Entity\UserBankAccount $bank)
    {
        return $this->bank->removeElement($bank);
    }

    /**
     * Get bank.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBank()
    {
        return $this->bank;
    }



    /**
     * Set companyName.
     *
     * @param string|null $companyName
     *
     * @return UserPersonalInfo
     */
    public function setCompanyName($companyName = null)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName.
     *
     * @return string|null
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

 
    /**
     * Set companyType.
     *
     * @param \BaseBundle\Entity\BaseNoms $companyType
     *
     * @return UserAddress
     */
    public function setCompanyType(\BaseBundle\Entity\BaseNoms $companyType)
    {
        $this->companyType = $companyType;

        return $this;
    }

    /**
     * Get companyType.
     *
     * @return \BaseBundle\Entity\BaseNoms
     */
    public function getCompanyType()
    {
        return $this->companyType;
    }

  

    /**
     * Set companyID.
     *
     * @param string|null $companyID
     *
     * @return UserPersonalInfo
     */
    public function setCompanyID($companyID = null)
    {
        $this->companyID = $companyID;

        return $this;
    }

    /**
     * Get companyID.
     *
     * @return string|null
     */
    public function getCompanyID()
    {
        return $this->companyID;
    }

    /**
     * Set companyVAT.
     *
     * @param string|null $companyVAT
     *
     * @return UserPersonalInfo
     */
    public function setCompanyVAT($companyVAT = null)
    {
        $this->companyVAT = $companyVAT;

        return $this;
    }

    /**
     * Get companyVAT.
     *
     * @return string|null
     */
    public function getCompanyVAT()
    {
        return $this->companyVAT;
    }

    /**
     * Set companyPerson.
     *
     * @param string|null $companyPerson
     *
     * @return UserPersonalInfo
     */
    public function setCompanyPerson($companyPerson = null)
    {
        $this->companyPerson = $companyPerson;

        return $this;
    }

    /**
     * Get companyPerson.
     *
     * @return string|null
     */
    public function getCompanyPerson()
    {
        return $this->companyPerson;
    }
    /**
     * Set companyPerson_phonetic.
     *
     * @param string|null $companyPerson_phonetic
     *
     * @return UserPersonalInfo
     */
    public function setCompanyPersonPhonetic($companyPerson_phonetic = null)
    {
        $this->companyPerson_phonetic = $companyPerson_phonetic;

        return $this;
    }

    /**
     * Get companyPerson_phonetic.
     *
     * @return string|null
     */
    public function getCompanyPersonPhonetic()
    {
        return $this->companyPerson_phonetic;
    }

    /**
     * Set personType.
     *
     * @param string $personType
     *
     * @return UserPersonalInfo
     */
    public function setPersonType($personType)
    {
        $this->personType = $personType;

        return $this;
    }

    /**
     * Get personType.
     *
     * @return Basenom
     */
    public function getPersonType()
    {
        return $this->personType;
    }

    /**
     * Get personSubType.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonSubType()
    {
        return $this->personSubType;
    }
    public function getPersonSubTypeRoles()
    {
        $result = [];
        if(!empty($this->personSubType)) {
            foreach ($this->personSubType as $key => $subType) {
                $result[] = $subType->getBnomKey();
            }
        }
        return $result;
    }
    /**
     * Get personSubType.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setPersonSubType($personSubType)
    {
        return $this->personSubType = $personSubType;
    }


    public function __toString() { return (string)$this->getFullName(); }

    /**
     * Add personSubType.
     *
     * @param \BaseBundle\Entity\BaseNoms $personSubType
     *
     * @return UserPersonalInfo
     */
    public function addPersonSubType(\BaseBundle\Entity\BaseNoms $personSubType)
    {
        $this->personSubType[] = $personSubType;

        return $this;
    }

    /**
     * Remove personSubType.
     *
     * @param \BaseBundle\Entity\BaseNoms $personSubType
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePersonSubType(\BaseBundle\Entity\BaseNoms $personSubType)
    {
        return $this->personSubType->removeElement($personSubType);
    }

   
    /**
     * Set dashboardSettings.
     *
     * @param array|null $dashboardSettings
     *
     * @return UserPersonalInfo
     */
    public function setDashboardSettings($dashboardSettings = null)
    {
        $this->dashboard_settings = $dashboardSettings;

        return $this;
    }

    /**
     * Get dashboardSettings.
     *
     * @return array|null
     */
    public function getDashboardSettings()
    {
        return $this->dashboard_settings;
    }

    /**
     * Set disabled.
     *
     * @param bool $disabled
     *
     * @return UserPersonalInfo
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled.
     *
     * @return bool
     */
    public function getDisabled()
    {
        return $this->disabled;
    }


    /**
     * Set credit.
     *
     * @param string|null $credit
     *
     * @return UserPersonalInfo
     */
    public function setCredit($credit = null)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit.
     *
     * @return string|null
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set delayed_payment.
     *
     * @param string|null $delayed_payment
     *
     * @return UserPersonalInfo
     */
    public function setDelayedPayment($delayed_payment = null)
    {
        $this->delayed_payment = $delayed_payment;

        return $this;
    }

    /**
     * Get delayed_payment.
     *
     * @return string|null
     */
    public function getDelayedPayment()
    {
        return $this->delayed_payment;
    }

    /**
     * Add image.
     *
     * @param \FileBundle\Entity\File $image
     *
     * @return UserPersonalInfo
     */
    public function addImage(\FileBundle\Entity\File $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \FileBundle\Entity\File $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\FileBundle\Entity\File $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    public function getImage($type)
    {
        foreach ($this->images as $image) {
            $key = $image->getBnomType1()->getBnomKey();
            if ($key=='user-image types-'.$type || $key==$type) {
                return $image;
            }
        }
        return null;
    }

    public function getAvatar() {
        return $this->getImage('avatar');
    }

   
}
