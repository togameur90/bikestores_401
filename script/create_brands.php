<?php

require __DIR__ . "/../config/bootstrap.php";

use App\Entity\Brand;


$values = [
    ['Electra'],
    ['Haro'],
    ['Heller'],
    ['Pure Cycles'],
    ['Ritchey'],
    ['Strider'],
    ['Sun Bicycles'],
    ['Surly'],
    ['Trek']
];

foreach ($values as $donnees) {
    $brand = new Brand();
    $brand->setBrandName($donnees[0]);   

    $entityManager->persist($brand);
}

$entityManager->flush();

echo "Toutes les marques ont été insérées avec succès !";
?>