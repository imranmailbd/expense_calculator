Expense Calculator
==========================

Installation:

1. place the application folder on root of server or any where else of your web server

2. according to your application folder placement path, set data source path in index.php file at line no 34

3. Browse the application according to application located on your server. [example: http://localhost/exp_calculator_app_imran]


Uses:

1. To process more bill please feel free to edit or add more node on the jsondata.json file located on root.
Please add as much node as you need on the JSON file to process more bills.

2. Qus no.1 : In total, how much is Tommen owed by everyone else? 
Ans: call method "get_person_owed" on line no 94 in index.php file to process the ans.
Comments: To get anther Payer owed amount change "person_owed" property value at 92 no line in index.php file

3. Qus no.2 : Qus no.2 : How much does Ola owe Sam? 
Ans: call method "get_person_net_owe" on line no 108 in index.php file to process the ans.
Comments: To get anther ower owes amount change "person_owe" (line:104) and "person_owed" (line:106) properties value index.php file

4. Qus no.3 : How much does Tommen owe Kelly?
Ans: Same as point :3

5. Qus no.4 : How can the group settle outstanding balance with the minimum number of payments?
Ans: call the mothod "sattle_group_expense" at 133 no line on index.php