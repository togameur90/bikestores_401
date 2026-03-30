<?php

require __DIR__ . "/../config/bootstrap.php";

use App\Entity\Store;


$values = [
    ['Santa Cruz Bikes', '(831) 476-4321', 'santacruz@bikes.shop', '3700 Portola Drive', 'Santa Cruz', 'CA', 95060],
    ['Baldwin Bikes', '(516) 379-8888', 'baldwin@bikes.shop', '4200 Chestnut Lane', 'Baldwin', 'NY', 11432],
    ['Rowlett Bikes', '(972) 530-5555', 'rowlett@bikes.shop', '8000 Fairway Avenue', 'Rowlett', 'TX', 75088]
];

foreach ($values as $donnees) {
    $store = new Store();
    
    $store->setStoreName($donnees[0]);
    $store->setPhone($donnees[1]);
    $store->setEmail($donnees[2]);
    $store->setStreet($donnees[3]);
    $store->setCity($donnees[4]);
    $store->setState($donnees[5]);
    $store->setZipCode($donnees[6]);

    $entityManager->persist($store);
}

$entityManager->flush();

echo "Tous les magasins ont été insérés avec succès !";
?>