var SQL = {
    createDependencyProposals: function (range){
        return [
              // Select Options
            {
                label          : '"select"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Consulta datos en tabla',
                insertText     : 'Select * from ${1:table_name}',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"select - where"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Consulta datos en tabla con condiciones',
                insertText     : 'Select * from ${1:table_name} where ${2:condition}',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // where Operator
            {
                label          : '"where with operator"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Columna = valor',
                insertText     : 'Select * from ${1:table_name} where ${2:column} = ${2:value}',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"where in"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Columna in (lista de valores)',
                insertText     : 'Select * from ${1:table_name} where ${2:column} in (${2:values})',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Tables Options
            {
                label          : '"create database"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Crea una base de datos nueva',
                insertText     : 'CREATE DATABASE ${1:database_name}',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"create table"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Crea una tabla nueva en la base de datos.',
                insertText     : 'CREATE TABLE ${1:table_name} ( \n ${2:column1} ${3:datatype} \n);',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"create table if not exists"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Crea una tabla si no existe previamente en la base de datos',
                insertText     : 'CREATE IF NOT EXISTS TABLE ${1:table_name} ( \n ${2:column1} ${3:datatype} \n);',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"drop table"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Elimina una tabla previamente creada en la base de datos.',
                insertText     : 'DROP TABLE ${1:table_name};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"drop table if exists"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Busca una tabla y si existe la elimina',
                insertText     : 'DROP TABLE IF EXISTS ${1:table_name};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Insert Options
            {
                label          : '"insert"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Inserta una nueva fila en la tabla',
                insertText     : 'INSERT INTO ${1:table_name} (${2:column1}, ${3:column2}) VALUES (${4:value1}, ${5:value2});',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"insert multiple rows"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Inserta varias filas en la tabla',
                insertText     : 'INSERT INTO ${1:table_name} (${2:column1}, ${3:column2}) VALUES (${4:value1}, ${5:value2}), (${6:value3}, ${7:value4});',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Update Options
            {
                label          : '"update"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Actualiza filas en la tabla',
                insertText     : 'UPDATE ${1:table_name} SET ${2:column1} = ${3:value1} WHERE ${4:condition};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Delete Options
            {
                label          : '"delete"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Elimina filas en la tabla',
                insertText     : 'DELETE FROM ${1:table_name} WHERE ${2:condition};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Join Options
            {
                label          : '"inner join"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Realiza un INNER JOIN entre dos tablas',
                insertText     : 'SELECT * FROM ${1:table1} INNER JOIN ${2:table2} ON ${1:table1}.${3:column1} = ${2:table2}.${4:column2};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"left join"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Realiza un LEFT JOIN entre dos tablas",
                insertText     : "SELECT * FROM ${1:table1} LEFT JOIN ${2:table2} ON ${1:table1}.${3:column1} = ${2:table2}.${4:column2};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"right join"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Realiza un RIGHT JOIN entre dos tablas',
                insertText     : 'SELECT * FROM ${1:table1} RIGHT JOIN ${2:table2} ON ${1:table1}.${3:column1} = ${2:table2}.${4:column2};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"full outer join"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Realiza un FULL OUTER JOIN entre dos tablas (no soportado nativamente en MySQL, se emula con UNION)',
                insertText     : 'SELECT * FROM ${1:table1} LEFT JOIN ${2:table2} ON ${1:table1}.${3:column1} = ${2:table2}.${4:column2} UNION SELECT * FROM ${1:table1} RIGHT JOIN ${2:table2} ON ${1:table1}.${3:column1} = ${2:table2}.${4:column2};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Aggregate Functions
            {
                label          : '"count"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Cuenta el número de filas que cumplen con la condición',
                insertText     : 'SELECT COUNT(${1:column}) FROM ${2:table_name} WHERE ${3:condition};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"sum"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Suma los valores de una columna',
                insertText     : 'SELECT SUM(${1:column}) FROM ${2:table_name} WHERE ${3:condition};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"avg"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Calcula el promedio de los valores de una columna',
                insertText     : 'SELECT AVG(${1:column}) FROM ${2:table_name} WHERE ${3:condition};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"min"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'Encuentra el valor mínimo en una columna',
                insertText     : 'SELECT MIN(${1:column}) FROM ${2:table_name} WHERE ${3:condition};',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"max"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Encuentra el valor máximo en una columna",
                insertText     : "SELECT MAX(${1:column}) FROM ${2:table_name} WHERE ${3:condition};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Group By and Having
            {
                label          : '"group by"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Agrupa los resultados por una columna",
                insertText     : "SELECT ${1:column1}, ${2:aggregate_function}(${3:column2}) FROM ${4:table_name} GROUP BY ${1:column1};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"having"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Filtra los resultados de una consulta agrupada",
                insertText     : "SELECT ${1:column1}, ${2:aggregate_function}(${3:column2}) FROM ${4:table_name} GROUP BY ${1:column1} HAVING ${2:aggregate_function}(${3:column2}) ${5:operator} ${6:value};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Order By
            {
                label          : '"order by"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Ordena los resultados por una columna",
                insertText     : "SELECT * FROM ${1:table_name} ORDER BY ${2:column} ${3:ASC|DESC};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Limit
            {
                label          : '"limit"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Limita el número de resultados retornados",
                insertText     : "SELECT * FROM ${1:table_name} LIMIT ${2:number_of_rows};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // Offset
            {
                label          : '"offset"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Especifica el desplazamiento de inicio de los resultados",
                insertText     : "SELECT * FROM ${1:table_name} LIMIT ${2:number_of_rows} OFFSET ${3:offset};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // DDL: Index
            {
                label          : '"create index"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Crea un índice en una columna",
                insertText     : "CREATE INDEX ${1:index_name} ON ${2:table_name} (${3:column_name});",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"drop index"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Elimina un índice de una tabla",
                insertText     : "DROP INDEX ${1:index_name} ON ${2:table_name};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // DCL: Grant and Revoke
            {
                label          : '"grant"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Otorga permisos a un usuario",
                insertText     : "GRANT ${1:privileges} ON ${2:database_name}.${3:table_name} TO '${4:user}'@'${5:host}';",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"revoke"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Revoca permisos a un usuario",
                insertText     : "REVOKE ${1:privileges} ON ${2:database_name}.${3:table_name} FROM '${4:user}'@'${5:host}';",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
              // TCL: Commit, Rollback, and Savepoint
            {
                label          : '"commit"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Confirma las transacciones pendientes",
                insertText     : "COMMIT;",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"rollback"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Deshace las transacciones pendientes",
                insertText     : "ROLLBACK;",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"savepoint"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Crea un punto de guardado en la transacción actual",
                insertText     : "SAVEPOINT ${1:savepoint_name};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"rollback to savepoint"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Deshace las transacciones hasta un punto de guardado específico",
                insertText     : "ROLLBACK TO ${1:savepoint_name};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"release savepoint"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : "Libera un punto de guardado",
                insertText     : "RELEASE SAVEPOINT ${1:savepoint_name};",
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            }
        ]; 
    }
}