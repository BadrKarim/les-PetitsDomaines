<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->entityManager = $entityManager;
    }
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'En cours de préparation', 'fas fa-box-open')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Expediée', 'fas fa-truck')->linkToCrudAction('updateDelivery');

        
        return $actions
            ->add('detail', $updatePreparation)
            ->add('detail', $updateDelivery)
            ->add('index', 'detail');
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        //$this->addFlash('notice', "<span style='color:green;'><strong>La commande '.$order->getReference().' est sous le statut <u>En cours de préparation</u>.</strong></span>");

        $url = $this->adminUrlGenerator
                        ->setController(OrderCrudController::class)
                        ->setAction('index')
                        ->generateUrl();

        // envoyer un mail

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        //$this->addFlash('notice', "<span style='color:orange;'><strong>La commande '.$order->getReference().' est sous le statut <u>Expédiée</u>.</strong></span>");

        $url = $this->adminUrlGenerator
                        ->setController(OrderCrudController::class)
                        ->setAction('index')
                        ->generateUrl();

        // envoyer un mail

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Identifiant de la commande :'),
            DateTimeField::new('createdAt', 'Commande passé le :'),
            TextField::new('user.fullName', 'Utilisateur :'),
            TextEditorField::new('delivery', 'Adresse de livraison')->setTrixEditorConfig(['tag' => ['tagName' => 'br']])->onlyOnDetail(),
            MoneyField::new('total', 'Total :')
                ->setCurrency('EUR'),
            
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de Port')
                ->setCurrency('EUR'),

            ChoiceField::new('state', 'Status de la commande')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'En cours de préparation' => 2,
                'Expediée' => 3
            ]),

            ArrayField::new('orderDetails', 'Liste des produits achetés')->hideOnIndex()
            ]
        ;
    }
    
}
