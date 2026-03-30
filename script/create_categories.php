<?php

require __DIR__ . "/../config/bootstrap.php";

use App\Entity\Category;


$values = [
    ['Children Bicycles'],
    ['Comfort Bicycles'],
    ['Cruisers Bicycles'],
    ['Cyclocross Bicycles'],
    ['Electric Bikes'],
    ['Mountain Bikes'],
    ['Road Bikes']
];

foreach ($values as $donnees) {
    $category = new Category();
    $category->setCategoryName($donnees[0]);   

    $entityManager->persist($category);
}

$entityManager->flush();

echo "Toutes les catégories ont été insérées avec succès !";
?>