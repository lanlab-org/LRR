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


*Last modified on 1 June 2020 by Hui*
