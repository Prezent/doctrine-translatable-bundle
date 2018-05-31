<?php

/*
 * (c) Prezent Internet B.V. <info@prezent.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prezent\Doctrine\TranslatableBundle\Filter;

use Doctrine\ORM\Query\Expr;
use Prezent\Doctrine\Translatable\EventListener\TranslatableListener;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;
use Symfony\Component\HttpFoundation\Request;

/**
 * TranslatableFilter
 *
 * @see StringFilter
 */
class TranslatableFilter extends StringFilter
{
    /**
     * @var TranslatableListener
     */
    private $listener;

    /**
     * Constructor
     *
     * @param TranslatableListener $listener
     */
    public function __construct(TranslatableListener $listener)
    {
        $this->listener = $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data)) {
            return;
        }

        $data['value'] = trim($data['value']);

        if (strlen($data['value']) == 0) {
            return;
        }

        $data['type'] = !isset($data['type']) ?  ChoiceType::TYPE_CONTAINS : $data['type'];
        $operator = $this->getOperator((int) $data['type']);

        if (!$operator) {
            $operator = 'LIKE';
        }

        $entities = $queryBuilder->getRootEntities();
        $classMetadata = $this->listener->getTranslatableMetadata(current($entities));
        $transMetadata = $this->listener->getTranslatableMetadata($classMetadata->targetEntity);

        // Add inner join
        if (!$this->hasJoin($queryBuilder, $alias)) {
            $parameterName = $this->getNewParameterName($queryBuilder);

            $queryBuilder->innerJoin(
                sprintf('%s.%s', $alias, $classMetadata->translations->name),
                'trans',
                Expr\Join::WITH,
                sprintf('trans.%s = :%s', $transMetadata->locale->name, $parameterName)
            );

            $queryBuilder->setParameter($parameterName, $this->listener->getCurrentLocale());
        }

        // c.name > '1' => c.name OPERATOR :FIELDNAME
        $parameterName = $this->getNewParameterName($queryBuilder);

        $or = $queryBuilder->expr()->orX();

        $or->add(sprintf('%s.%s %s :%s', 'trans', $field, $operator, $parameterName));

        if (ChoiceType::TYPE_NOT_CONTAINS == $data['type']) {
            $or->add($queryBuilder->expr()->isNull(sprintf('%s.%s', 'trans', $field)));
        }

        $this->applyWhere($queryBuilder, $or);

        if ($data['type'] == ChoiceType::TYPE_EQUAL) {
            $queryBuilder->setParameter($parameterName, $data['value']);
        } else {
            $queryBuilder->setParameter($parameterName, sprintf($this->getOption('format'), $data['value']));
        }
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function getOperator($type)
    {
        $choices = array(
            ChoiceType::TYPE_CONTAINS         => 'LIKE',
            ChoiceType::TYPE_NOT_CONTAINS     => 'NOT LIKE',
            ChoiceType::TYPE_EQUAL            => '=',
        );

        return isset($choices[$type]) ? $choices[$type] : false;
    }

    /**
     * Does the query builder have a translation join
     *
     * @param ProxyQueryInterface $queryBuilder
     * @return bool
     */
    private function hasJoin(ProxyQueryInterface $queryBuilder, $alias)
    {
        $joins = $queryBuilder->getDQLPart('join');

        if (!isset($joins[$alias])) {
            return false;
        }

        foreach ($joins[$alias] as $join) {
            if ('trans' === $join->getAlias()) {
                return true;
            }
        }

        return false;
    }
}
