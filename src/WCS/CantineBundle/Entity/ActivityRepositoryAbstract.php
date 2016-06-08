<?php
namespace WCS\CantineBundle\Entity;


use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ActivityRepositoryAbstract extends \Doctrine\ORM\EntityRepository
{
    /**
     * @var OptionsResolver
     */
    private $dayListResolver;

    /**
     * ActivityRepositoryAbstract constructor.
     *
     * @inheritdoc
     */
    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        $this->dayListResolver = new OptionsResolver();

        $this->dayListResolver->setRequired(array(
            'date_day',
            'school'
        ));

        $this->configureDayListOptions($this->dayListResolver);

        parent::__construct($em, $class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureDayListOptions(OptionsResolver $resolver)
    {}

    /**
     * @param $options
     */
    public function getActivityDayList($options)
    {
        $options = $this->dayListResolver->resolve($options);
        return $this->getDayList($options);
    }

    /**
     * @param $options
     */
    abstract protected function getDayList($options);
}
