<?php

require __DIR__ . "/../config/bootstrap.php";

use App\Entity\Employee;
use App\Entity\Store;


$values = [
    [1, 'John Doe', 'johndoe@bikestore.com', 'KQ,wDqd9iGtt', 'employee'],
    [1, 'David Costa', 'davidcosta@bikestore.com', 'YDH&jXaDE3Jv', 'employee'],
    [1, 'Todd Martell', 'toddmartell@bikestore.com', 'qzFxrnH^dmh4', 'employee'],
    [1, 'Adela Marion', 'adelamarion@bikestore.com', 'gxv{4nKJvLUt', 'employee'],
    [1, 'Matthew Popp', 'matthewpopp@bikestore.com', 'cbaiWMx8}dFq', 'employee'],
    [2, 'Alan Wallin', 'alanwallin@bikestore.com', 'kEyBdZVY@4Ac', 'employee'],
    [2, 'Joyce Hinze', 'joycehinze@bikestore.com', 'JMm2rRvd#Jup', 'employee'],
    [2, 'Donna Andrews', 'donnaandrews@bikestore.com', 'KeMMyic;X9zp', 'employee'],
    [2, 'Andrew Best', 'andrewbest@bikestore.com', 'XRTNP(mGTKF7', 'employee'],
    [2, 'Joel Ogle', 'joelogle@bikestore.com', '4eXVWEB%akcb', 'employee'],
    [3, 'Sam maraz', 'sammaraz@bikestore.com', 'p8vxkB[ryDtK', 'employee'],
    [3, 'Lili Hirt', 'lilihurt@bikestore.com', 'trtVgf9R{Cvw', 'employee'],
    [3, 'Michael Stone', 'michaelstone@bikestore.com', 'NWJs2wnmnm%V', 'employee'],
    [3, 'Krystel Wolf', 'krystelwolf@bikestore.com', 'LvUiR>kk2dWD', 'employee'],
    [3, 'Neva Guttman', 'nevaguttman@bikestore.com', '@o8cmHRyeECm', 'employee'],
    [1, 'Davonte Meyer', 'davontemeyer@bikestore.com', 'FR;NuetQu9wE', 'chief'],
    [2, 'Joseph Quitz', 'josephquitz@bikestore.com', 'mFLjH6fPaU^E', 'chief'],
    [3, 'Jeremie Roth', 'jeremieroth@bikestore.com', 'Hvi>wshhGa2c', 'chief'],
    [1, 'Shannah Summer', 'shannahsummer@bikestore.com', 'TVv(cB4mBEiC', 'it']
];

foreach ($values as $donnees) {
    $employee = new Employee();
    
    
    $store = $entityManager->getRepository(Store::class)->find($donnees[0]);
    $employee->setStore($store);

    $employee->setEmployeeName($donnees[1]);
    $employee->setEmployeeEmail($donnees[2]);
    $employee->setEmployeePassword($donnees[3]);
    $employee->setEmployeeRole($donnees[4]);

    $entityManager->persist($employee);
}

$entityManager->flush();

echo "Tous les employés ont été insérés avec succès !";
?>