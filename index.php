<?php

/**
 * SattleGroupExpense - An expense calculator application
 *
 * @author   Muhammed Imran <imranmailbd@gmail.com>
 *
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| For load other class related to our application we use autoloader.
| Autoloader load our class when that class instantiated. We do not
| include that class file manually.
|
*/

file_exists('autoload.php') ? require_once('autoload.php') : die('error : autoload.php was not found.');



/*
|--------------------------------------------------------------------------
| Set data source path here
|--------------------------------------------------------------------------
|
| here we set the json data source path use as main data source of the application.
|
*/

$data_source_path = 'http://i4reactor.com/expense_calculator/jsondata.json'; //http://localhost/exp_calculator_app_imran/jsondata.json


/*
|--------------------------------------------------------------------------
| Create A Dependency Class Instance for the Application
|--------------------------------------------------------------------------
|
| Here we create a new instance of SortedNearest class that use as algorithm to calculate Shortest Nearest number.
| This is a dependency class instance that we inject in out Application Class as dependency injection.
| put that object instance in a variable for use as dependencies of other class.
|
*/

$sortNearest = new SortedNearest();




/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we create a new Expense Calculator application instance, and then
| put that object instance in a variable for next use.
|
*/

$group_expense = new SattleGroupExpense($sortNearest,$data_source_path);






/*
|--------------------------------------------------------------------------
| Now it is time to give the input for the system
|--------------------------------------------------------------------------
|
| The system has huge extendability of its functionalities. Now we give 
| all calling here this place for get expected output.
| 
| Our expected output are belows:
| 1. In total, how much is Tommen owed by everyone else?
| 2. How much does Ola owe Sam
| 3. How much does Tommen owe Kelly
| 4. How can the group settle outstanding balance with the minimum number of payments?
|
*/

/*
* call process with argument to calculate and create the answer for Qus no.1
* Qus no.1 : In total, how much is Tommen owed by everyone else? 
* here we give the name as input what we expect as output. As expected we use Tommen here.
* Please feel free to use other payer name here as your reqirements
*/
$person_owed = "Tommen";

$group_expense->get_person_owed($person_owed);



/*
 * call process with argument to calculate and create the answer for Qus no.2
 * Qus no.2 : How much does Ola owe Sam?
 * here we give the name as input what we expect as output. As expected we use "Ola" as ower and "Sam" as payer here.
 * Please feel free to use other ower and payer name here as your reqirements
 */
$person_owe = "Ola";

$person_owed = "Sam";

$group_expense->get_person_net_owe($person_owe, $person_owed);




/*
 * call process with argument to calculate and create the answer for Qus no.3
 * Qus no.3 : How much does Tommen owe Kelly
 * here we give the name as input what we expect as output. As expected we use "Tommen" as ower and "Kelly" as payer here.
 * Please feel free to use other ower and payer name here as your reqirements
 */
$person_owe = "Tommen";

$person_owed = "Kelly";

$group_expense->get_person_net_owe($person_owe, $person_owed);



/*
 * call the process to calculate and create the answer for Qus no.4
 * Qus no.4 : How can the group settle outstanding balance with the minimum number of payments?
 * 
 */

$group_expense->sattle_group_expense();




