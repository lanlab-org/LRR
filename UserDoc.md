LRR User Documentation
======================


Resetting password
-------------------

We can reset a user's password by directly modifying the MySQL database table called `users_table`.  More specifically, we delete that user's information from `users_table` so that the user could sign up again.  Suppose the user's student number is 201131129138.

To do so, LRR administrator logs in to MySQL using the following command:  `mysql -u username -p`.  Type the correct password to access the MySQL database.

After that, issue the following commands in the mysql prompt.

- `use lrr;`

- `delete from users_table where Student_ID="201131129138";`

The first one uses a database called lrr in MySQL.  The second one deletes a record from `users_table` where the student number is 201131129138.


Joining by using course name or course
--------------------------------------

In this section we are going to present what we did on the course page to allowed the users(students) to find the course and join that course using the course name/course code.
we have created a new page with some links (home, course, disabled).those links will help the users to navigate through the other pages of LRRS.

we have use the lrr database especially the courses_table that store different courses information.

You can have a look on our new code source (home.php, connect.php, verif-form.php,connect.php) for more details.

we have not yet associated our page with other pages(script.php) of LRRS because this page is quite different to other pages.

 have a look on our user guide file(user guide file) to see some screenshots of our page.


*Last modified on 1 June 2020 by Hui*
