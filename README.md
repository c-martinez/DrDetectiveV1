# Dr. Detective

Dr. Detective is an online game for annotating medical texts. The game is designed with the purpose of engaging medical experts into solving annotation tasks on medical case reports, tailored to capture disagreement between annotators. It incorporates incentives such as learning features, to motivate a continuous involvement of the expert crowd.

The game was designed to identify expressions valuable for training natural language processing (NLP) tools, and interpret their relation in the context of medical diagnosing. In this way, we can resolve the main problem in gathering ground truth from experts that the low inter-annotator agreement is typically caused by different interpretations of the text.

For further details on Dr. Detective game, please refer to
["Dr. Detective": combining gamification techniques and crowdsourcing to create a gold standard in medical text](http://ceur-ws.org/Vol-1030/paper-02.pdf).

## Distribution

The source code for the game is provided through this github repository ?UNDER A GNU GPL LICENCE?. The repository contains the source code (PHP) and a database (MySQL) export.

To create your own copy of Dr. Detective, just copy the source code to a suitable location on your web server, and import the database to your MySQL instance.

NOTE: if you want to change the MySQL connection details, you will need to edit mysql.php files accordingly.