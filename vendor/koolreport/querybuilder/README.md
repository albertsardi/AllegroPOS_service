# Introduction

`QueryBuilder` helps you to generate SQL Query using pure PHP code. The benefits of using this package are:

1. To create faster, better and more precise SQL query without any syntax error.
2. To overcome to complexity of SQL even if you are expert.
3. To prevent your data from security risks such as sql injection.

If you are familiar with `Laravel`, an famous PHP Framework, you will like this package very much because you will not need to learn how to use this package. 

# Installation

## By downloading .zip file

1. [Download](https://www.koolreport.com/packages/querybuilder)
2. Unzip the zip file
3. Copy the folder `querybuilder` into `koolreport` folder so that look like below

```bash
koolreport
├── core
├── querybuilder
```

## By composer

```
composer require koolreport/querybuilder
```

# Documentation

## Generate compatible SQL query for different database systems

`QueryBuilder` package support `MySQL`, `PostgreSQL`, `SQLServer` query type.

#### MySQL Query

Use may cover your query in `MySQL::type()` function to get SQL in string or use `toMySQL()` of the query.

```
$this->src('mysql_database')->query(MySQL::type(
    DB::table('orders')
))

$this->src('mysql_database')->query(
    DB::table('orders')->toMySQL()
)
```

#### PostgreSQL Query

Use may cover your query in `PostgreSQL::type()` function to get SQL in string or use `toPostgreSQL()` of the query.

```
$this->src('mysql_database')->query(PostgreSQL::type(
    DB::table('orders')
))

$this->src('postgresql_database')->query(
    DB::table('orders')->toPostgreSQL()
)
```

#### SQLServer Query

Use may cover your query in `SQLServer::type()` function to get SQL in string or use `toSQLServer()` of the query.

```
$this->src('sqlserver_database')->query(SQLServer::type(
    DB::table('orders')
))

$this->src('sqlserver_database')->query(
    DB::table('orders')->toSQLServer()
)
```

#### Parameterized Query and Parameters (version >= 3.0.0)

When you build a query builder with data from untrusted source (says, user inputs) it's dangerous to use the query builder's generated query directly because of possible SQL injection attack. In those cases it's advisable to get the query builder's generated parameterized query together with parameters and use them to get data instead:

```
$querybuilder = DB::...;

$queryWithParams = $querybuilder->toMySQL(["useSQLParams" => "name"]); // or "useSQLParams" => "question mark"
$params = $querybuilder->getSQLParams();
```

## Set Schemas

For security and authentication reasons users could set a query builder's schemas so that only tables and fields from those schemas are included in its generated queries:

```
$querybuilder = DB::...;

$querybuilder->setSchemas(array(
    "salesSchema" => array(
        "tables" => array(
            "customers"=>array(
                "customerNumber"=>array(
                    "alias"=>"Customer Number",
                ),
                "customerName"=>array(
                    "alias"=>"Customer Name",
                ),
            ),
            "orders"=>array(
                "orderNumber"=>array(
                    "alias"=>"Order Number"
                ),
                "orderDate"=>array(
                    "alias"=>"Order Date",
                    "type" => "datetime"
                ),
                "orderMonth" => [
                    "expression" => "month(orderDate)",
                ]
            ),
            ...
        ),
    ),
    ...
));
```

## Retrieving Results

#### Retrieving All Rows From A Table

You may use the `table` method on the `DB` facade to begin a query. The `table` method returns a fluent query builder instance for the given table, allowing you to chain more constraints onto the query.

```

use \koolreport\querybuilder\DB;
use \koolreport\querybuilder\MySQL;

class MyReport extends \koolreport\KoolReport
{
    function settings()
    {
        return array(
            "dataSources"=>array(
                "automaker"=>array(
                    "connectionString"=>"mysql:host=localhost;dbname=automaker",
                    "username"=>"root",
                    "password"=>"",
                    "charset"=>"utf8"
                ),
            )
        );
    }
    function setup()
    {
        $this->src('automaker')->query(MySQL::type(
            DB::table("payments") // Equivalent to : "SELECT * FROM payments"
        ))
        ->pipe($this->dataStore('payments'));
    }
}

```

#### Retrieving A Single Row

If you just need to retrieve a single row from the database table, you may use the `first` method.

```
DB::table('users')->where('name', 'John')->first()

// Equivalent: "SELECT * FROM users WHERE `name`='John' LIMIT 1"
```

## Aggregates

The query builder also provides a variety of aggregate methods such as `count`, `max`,  `min`, `avg`, and `sum`. You may call any of these methods after constructing your query:

```
DB::table('orders')->groupBy('country')->sum('amount')

DB::table('orders')->count()

DB::table('customers')->groupBy('state')
    ->avg('income')->alias('avgIncome')
```

## Sub query table

`QueryBuilder` support creating sub query. Meaning that you can query from a table generated by another query.

```
DB::table([
    'orders',
    't'=>function($query){
        $query->select('name','age')->from('customers');
    }]
)->...
```

Above will generate:

```
SELECT *
FROM orders, (SELECT name,age FROM customer) t
```


## Selects

#### Specifying A Select Clause

Of course, you may not always want to select all columns from a database table. Using the `select` method, you can specify a custom `select` clause for the query:

```
DB::table('users')->select('name', 'email')
```

To change name of column, you may use `alias` function

```
DB::table('users')
    ->select('customerName')->alias('name')
    ->addSelect('customerAge')->alias('age')
```

The `distinct` method allows you to force the query to return distinct results:

```
DB::table('users')->distinct()
```

If you already have a query builder instance and you wish to add a column to its existing select clause, you may use the `addSelect` method or simple use continuously `select` method:

```
DB::table('users')->select('name')->addSelect('age')
```

## Raw Expressions

Sometimes you may need to use a raw expression in a query.

#### selectRaw

The `selectRaw` method can be used to create raw select. This method accepts an optional array of bindings as its second argument:

```
DB::table('orders')->selectRaw('price * ? as price_with_tax', [1.0825])
```

#### whereRaw / orWhereRaw

The `whereRaw` and `orWhereRaw` methods can be used to inject a raw `where` clause into your query. These methods accept an optional array of bindings as their second argument:

```
DB::table('orders')->whereRaw('price > IF(state = "TX", ?, 100)', [200])
```

#### havingRaw / orHavingRaw

The `havingRaw` and `orHavingRaw` methods may be used to set a raw string as the value of the `having` clause:

```
DB::table('orders')
    ->select('department')
    ->sum('price')->alias('total_sales')
    ->groupBy('department')
    ->havingRaw('SUM(price) > 2500')
```

#### orderByRaw

The `orderByRaw` method may be used to set a raw string as the value of the `order` by clause:

```
DB::table('orders')
    ->orderByRaw('updated_at - created_at DESC')
```

## Joins

#### Inner Join Clause

The query builder may also be used to write join statements. To perform a basic "inner join", you may use the `join` method or `innerJoin` on a query builder instance. The first argument passed to the `join` method is the name of the table you need to join to, while the remaining arguments specify the column constraints for the join. Of course, as you can see, you can join to multiple tables in a single query:    

```
DB::table('users')
    ->join('contacts', 'users.id', '=', 'contacts.user_id')
    ->join('orders', 'users.id', '=', 'orders.user_id')
    ->select('users.*', 'contacts.phone', 'orders.price')
```

#### leftJoin/rightJoin/outerJoin

```
DB::table('users')
    ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
```

#### crossJoin

To perform a "cross join" use the `crossJoin` method with the name of the table you wish to cross join to. Cross joins generate a cartesian product between the first table and the joined table:

```
DB::table('sizes')
    ->crossJoin('colours')
```

#### Advanced Join Clauses

You may also specify more advanced join clauses. To get started, pass a `Closure` as the second argument into the `join` method. The `Closure` will receive a `JoinClause` object which allows you to specify constraints on the `join` clause:

```
DB::table('users')
    ->join('contacts', function ($join) {
        $join->on('users.id', '=', 'contacts.user_id')->orOn(...);
    })
```

If you would like to use a "where" style clause on your joins, you may use the `where` and  `orWhere` methods on a join. Instead of comparing two columns, these methods will compare the column against a value:

```
DB::table('users')
    ->join('contacts', function ($join) {
        $join->on('users.id', '=', 'contacts.user_id')
                 ->where('contacts.user_id', '>', 5);
    })
```

## Unions

The query builder also provides a quick way to "union" two queries together. For example, you may create an initial query and use the union method to `union` it with a second query:

```
DB::table('users')->whereNull('first_name')->union(
    DB::table('users')->whereNull('last_name')
);
```

## Where Clauses

#### Simple Where Clauses

You may use the `where` method on a query builder instance to add `where` clauses to the query. The most basic call to `where` requires three arguments. The first argument is the name of the column. The second argument is an operator, which can be any of the database's supported operators. Finally, the third argument is the value to evaluate against the column.

For example, here is a query that verifies the value of the "votes" column is equal to 100:

```
DB::table('users')->where('votes', '=', 100)
```

For convenience, if you simply want to verify that a column is equal to a given value, you may pass the value directly as the second argument to the `where` method:

```
DB::table('users')->where('votes', 100)
```

Of course, you may use a variety of other operators when writing a `where` clause:

```
DB::table('users')->where('votes', '>=', 100)

DB::table('users')->where('votes', '<>', 100)

DB::table('users')->where('name', 'like', 'T%')
```

You may also pass an array of conditions to the `where` function:

```
DB::table('users')->where([
    ['status', '=', '1'],
    ['subscribed', '<>', '1'],
])
```

#### Or Statements

You may chain where constraints together as well as add or clauses to the query. The  `orWhere` method accepts the same arguments as the `where` method:

```
DB::table('users')
    ->where('votes', '>', 100)
    ->orWhere('name', 'John')
```

#### Brackets in where

You could add opening and closing brackets to where clause with `whereOpenBracket` and `whereCloseBracket` methods:

```
DB::table('users')
    ->where(...)
    ->whereOpenBracket()
    ->where(...)
    ->whereCloseBracket()
```

These brackets can work for multiple levels of where conditions.

#### Additional Where Clauses

__whereBetween__

The `whereBetween` method verifies that a column's value is between two values:

```
DB::table('users')->whereBetween('votes', [1, 100])
```

__whereNotBetween__

The `whereNotBetween` method verifies that a column's value lies outside of two values:

```
DB::table('users')->whereNotBetween('votes', [1, 100])
```

__whereIn / whereNotIn__

The `whereIn` method verifies that a given column's value is contained within the given array:

```
DB::table('users')->whereIn('id', [1, 2, 3])
```

The `whereNotIn` method verifies that the given column's value is not contained in the given array:

```
DB::table('users')->whereNotIn('id', [1, 2, 3])
```

__whereNull / whereNotNull__

The `whereNull` method verifies that the value of the given column is `NULL`:

```
DB::table('users')->whereNull('updated_at')
```

The `whereNotNull` method verifies that the column's value is not `NULL`:

```
DB::table('users')->whereNotNull('updated_at')
```

__whereDate / whereMonth / whereDay / whereYear / whereTime__

The `whereDate` method may be used to compare a column's value against a date:

```
DB::table('users')->whereDate('created_at', '2016-12-31')
```

The `whereMonth` method may be used to compare a column's value against a specific month of a year:

```
DB::table('users')->whereMonth('created_at', '12')
```

The `whereDay` method may be used to compare a column's value against a specific day of a month:

```
DB::table('users')->whereDay('created_at', '31')
```

The `whereYear` method may be used to compare a column's value against a specific year:

```
DB::table('users')->whereYear('created_at', '2016')
```

The `whereTime` method may be used to compare a column's value against a specific time:

```
DB::table('users')->whereTime('created_at', '=', '11:20')
```

__whereColumn__

The `whereColumn` method may be used to verify that two columns are equal:

```
DB::table('users')->whereColumn('first_name', 'last_name')
```

You may also pass a comparison operator to the method:

```
DB::table('users')->whereColumn('updated_at', '>', 'created_at')
```

The `whereColumn` method can also be passed an array of multiple conditions. These conditions will be joined using the `and` operator:

```
DB::table('users')
    ->whereColumn([
        ['first_name', '=', 'last_name'],
        ['updated_at', '>', 'created_at']
])
```

## Parameter Grouping

Sometimes you may need to create more advanced where clauses such as "where exists" clauses or nested parameter groupings. The KoolReport query builder can handle these as well. To get started, let's look at an example of grouping constraints within parenthesis:

```
DB::table('users')
    ->where('name', '=', 'John')
    ->orWhere(function ($query) {
        $query->where('votes', '>', 100)
                ->where('title', '<>', 'Admin');
    })
```

As you can see, passing a `Closure` into the `orWhere` method instructs the query builder to begin a constraint group. The `Closure` will receive a query builder instance which you can use to set the constraints that should be contained within the parenthesis group. The example above will produce the following SQL:

```
select * from users where name = 'John' or (votes > 100 and title <> 'Admin')
```

## Where Exists Clauses

The `whereExists` method allows you to write `where exists` SQL clauses. The `whereExists` method accepts a `Closure` argument, which will receive a query builder instance allowing you to define the query that should be placed inside of the "exists" clause:

```
DB::table('users')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('orders')
                      ->whereRaw('orders.user_id = users.id');
            })
```

The query above will produce the following SQL:

```
select * from users
where exists (
    select 1 from orders where orders.user_id = users.id
)
```

## JSON Where Clauses

`QueryBuilder` package also supports querying JSON column types on databases that provide support for JSON column types. Currently, this includes MySQL 5.7 and PostgreSQL. To query a JSON column, use the `->` operator:

```
DB::table('users')
                ->where('options->language', 'en')

DB::table('users')
                ->where('preferences->dining->meal', 'salad')
```

## Ordering, Grouping, Limit, & Offset

#### orderBy

The `orderBy` method allows you to sort the result of the query by a given column. The first argument to the `orderBy` method should be the column you wish to sort by, while the second argument controls the direction of the sort and may be either `asc` or `desc`:

```
DB::table('users')
                ->orderBy('name', 'desc')
```

#### latest / oldest

The `latest` and `oldest` methods allow you to easily order results by date. By default, result will be ordered by the `created_at` column. Or, you may pass the column name that you wish to sort by:

```
DB::table('users')
        ->latest()
        ->first()
```

#### groupBy / having

The `groupBy` and `having` methods may be used to group the query results. The `having` method's signature is similar to that of the `where` method:

```
DB::table('users')
        ->groupBy('account_id')
        ->having('account_id', '>', 100)
```

You may pass multiple arguments to the `groupBy` method to group by multiple columns:

```
DB::table('users')
        ->groupBy('first_name', 'status')
        ->having('account_id', '>', 100)
```

For more advanced `having` statements, see the `havingRaw` method.

#### skip / take

To limit the number of results returned from the query, or to skip a given number of results in the query, you may use the `skip` and `take` methods:

```
DB::table('users')->skip(10)->take(5)
```

Alternatively, you may use the `limit` and `offset` methods:

```
DB::table('users')
        ->offset(10)
        ->limit(5)
```

## Conditional Clauses

#### when

Sometimes you may want clauses to apply to a query only when something else is true. For instance you may only want to apply a `where` statement if a given input value is present on the incoming request. You may accomplish this using the `when` method:

```
$role = $_POST['role'];

DB::table('users')
    ->when($role, function ($query) use ($role) {
        return $query->where('role_id', $role);
    })
```

The `when` method only executes the given Closure when the first parameter is `true`. If the first parameter is `false`, the Closure will not be executed.

You may pass another Closure as the third parameter to the `when` method. This Closure will execute if the first parameter evaluates as `false`. To illustrate how this feature may be used, we will use it to configure the default sorting of a query:

```
$sortBy = null;

$users = DB::table('users')
->when($sortBy, 
    function ($query) use ($sortBy) {
        return $query->orderBy($sortBy);
    },
    function ($query) {
        return $query->orderBy('name');
    }
)
```

#### branch

Sometime you may need clause to apply to query when a parameter has specific value, you may use the `branch` statement.

You will pass to the `branch` function the list of `Closure` in second parameters.

```
$user_role = "admin"; //"registered_user","public"

DB::table('orders')
    ->branch($user_role,[
        "admin"=>function($query){
            $query->whereIn('state',['TX','NY','DC'])
        },
        "registered_user"=>function($query){
            $query->whereIn('state',['TX','NY'])
        },
        "public"=>function($query){
            $query->where('state','TX')
        },        
    ])
```

## Inserts

Although working with KoolReport, most of the time you will deal with `select` statement, the query builder also provides an `insert` method for inserting records into the database table. The `insert` method accepts an array of column names and values:

```
DB::table('users')->insert(
    ['email' => 'john@example.com', 'votes' => 0]
);
```

You may even insert several records into the table with a single call to `insert` by passing an array of arrays. Each array represents a row to be inserted into the table:

```
DB::table('users')->insert([
    ['email' => 'taylor@example.com', 'votes' => 0],
    ['email' => 'dayle@example.com', 'votes' => 0]
]);
```

## Updates

Although working with KoolReport, most of the time you will deal with `select` statement, the query builder can also `update` existing records using the `update` method. The `update` method, like the `insert` method, accepts an array of column and value pairs containing the columns to be updated. You may constrain the `update` query using `where` clauses:

```
DB::table('users')
    ->where('id', 1)
    ->update(['votes' => 1]);
```

### Increment & Decrement

The query builder also provides convenient methods for incrementing or decrementing the value of a given column. This is a shortcut, providing a more expressive and terse interface compared to manually writing the `update` statement.

Both of these methods accept at least one argument: the column to modify. A second argument may optionally be passed to control the amount by which the column should be incremented or decremented:

```
DB::table('users')->increment('votes')

DB::table('users')->increment('votes', 5)

DB::table('users')->decrement('votes')

DB::table('users')->decrement('votes', 5)
```

## Deletes

Although working with KoolReport, most of the time you will deal with `select` statement,the query builder may also be used to `delete` records from the table via the `delete` method. You may constrain `delete` statements by adding `where` clauses before calling the `delete` method:

```
DB::table('users')->delete()

DB::table('users')->where('votes', '>', 100)->delete()
```

If you wish to truncate the entire table, which will remove all rows and reset the auto-incrementing ID to zero, you may use the `truncate` method:

```
DB::table('users')->truncate();
```

## Pessimistic Locking

The query builder also includes a few functions to help you do "pessimistic locking" on your `select` statements. To run the statement with a "shared lock", you may use the  `sharedLock` method on a query. A shared lock prevents the selected rows from being modified until your transaction commits:

```
DB::table('users')->where('votes', '>', 100)->sharedLock()
```

Alternatively, you may use the `lockForUpdate` method. A "for update" lock prevents the rows from being modified or from being selected with another shared lock:

```
DB::table('users')->where('votes', '>', 100)->lockForUpdate()
```

# Support

Please use our forum if you need support, by this way other people can benefit as well. If the support request need privacy, you may send email to us at __support@koolreport.com__.



