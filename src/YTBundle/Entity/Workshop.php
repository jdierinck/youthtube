<?php
namespace YTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Gedmo\Translatable\Translatable;

/**
 *@ORM\Entity(repositoryClass="YTBundle\Entity\WorkshopRepository")
 *@ORM\Table(name="workshop")
 */
class Workshop {
	
   /**
    *@ORM\Column(type="integer")
    *@ORM\Id
    *@ORM\GeneratedValue(strategy="AUTO")
    */
	protected $id;
	
   /**
    *@ORM\Column(type="string")
    * @Gedmo\Translatable
    */	
	protected $title;
	
   /**
    *@ORM\Column(type="string")
    * @Gedmo\Translatable
    */	
	protected $subtitle;
	
   /**
    *@ORM\Column(type="text")
    * @Gedmo\Translatable
    */	
	protected $description;
	
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }	
	
    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     * @Assert\File(
     *   maxSize="2048k",
     *   mimeTypes="image/*",
     *   mimeTypesMessage="Please upload a valid image file"
     * )
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
    * Move file and set path property
    */
    public function upload()
	{
		// the file property can be empty if the field is not required
		if (null === $this->getFile()) {
			return;
		}
	
		// move takes the target directory and then the
		// target filename to move to
		
		$dir = $this->getUploadRootDir();
		// use original file name, not secure:
		// $newname = $this->getFile()->getClientOriginalName();
		// sanitize name first:
		$oldname = $this->getFile()->getClientOriginalName();
		//$newname = filter_var($oldname,FILTER_SANITIZE_STRING);
		$newname = trim($oldname);
		$newname = htmlentities($newname,ENT_QUOTES);
		$newname = stripslashes($newname);
		$newname = strtolower($newname);
		$newname = str_replace(" ","_",$newname);
		$this->getFile()->move($dir,$newname);
	
		// set the path property to the filename where you've saved the file
		$this->path = $newname;
	
		// clean up the file property as you won't need it anymore
		$this->file = null;
	}
	
   /**
    *@ORM\Column(type="boolean")
    */	
	protected $continu;
	
	/**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;
    
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
	

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Workshop
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return Workshop
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Workshop
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Workshop
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set continu
     *
     * @param boolean $continu
     * @return Workshop
     */
    public function setContinu($continu)
    {
        $this->continu = $continu;

        return $this;
    }

    /**
     * Get continu
     *
     * @return boolean 
     */
    public function getContinu()
    {
        return $this->continu;
    }
    
    public function isContinu()
    {
        return $this->continu == true? true : false;
    }	
	   
}
