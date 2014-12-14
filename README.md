PostPile
========

PostPile was developed as a part of an assignment in a programming course, at Blekinge Techniska Högskola. The webpage primarelly dedicated to research-related discussion in fields of biomedical sciences. Anyone interested or doing research in this field may take part in discussions related to experiments, their protocols, or even recently-published reserach articles.


Installation
------------

Clone PostPile repo:

>    git clone https://github.com/agniukaitis/postpile.git


The package makes use of MySQL database. In order to setup the required tables for the first time, navigate to <code>root/app/config/database_mysql.php</code>, and enter your database connection details.


Once the connection is established, navigate to <code>webroot/users/setup</code> on a webbrowser to craete user table, and a standard admin account. Navigate to <code>webroot/questions/setup</code> and <code>webroot/tags/setup</code> to create question and tag table respectively.


In order to protect the databse tables being resetted by anyone, enable commented out section of setupAction methods located in <code>root/src/Users/UsersController.php</code>, <code>root/src/Questions/QuestionsController.php</code>, and <code>root/src/Tags/TagsController.php</code> files. This will make sure that only the Admin account may reset the aforementioned tables.


Lastly, navigate to <code>root/webroot/.htaccess</code> files, and enable/edit line 10 to read <code>RewriteBase /route/to/your/server/webroot/</code>.


After these steps, you should be up and running with your very own verion of PostPile.


PosPile build
-------------

PostPile is based on Anax-MVC PHP framework

Read about Anax-MVC here: ["Anax som MVC-ramverk"](http://dbwebb.se/kunskap/anax-som-mvc-ramverk) and here ["Bygg en me-sida med Anax-MVC"](http://dbwebb.se/kunskap/bygg-en-me-sida-med-anax-mvc). 

Builds upon Anax-base, read article about Anax-base ["Anax - en hållbar struktur för dina webbapplikationer"](http://dbwebb.se/kunskap/anax-en-hallbar-struktur-for-dina-webbapplikationer) to get an overview of its none-MVC variant. 

The framework was develped by Mikael Roos, me@mikaelroos.se.




License 
-------

This software is free software and carries a MIT license.



Use of external libraries
-----------------------------------

The following external modules are included and subject to its own license.



### Modernizr
* Website: http://modernizr.com/
* Version: 2.6.2
* License: MIT license 
* Path: included in `webroot/js/modernizr.js`



### PHP Markdown
* Website: http://michelf.ca/projects/php-markdown/
* Version: 1.4.0, November 29, 2013
* License: PHP Markdown Lib Copyright © 2004-2013 Michel Fortin http://michelf.ca/ 
* Path: included in `3pp/php-markdown`



```
 .  
..:  Copyright (c) 2013 - 2014 Julius Semenas, semius@gmail.com
```


