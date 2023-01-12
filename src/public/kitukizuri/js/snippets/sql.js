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

        ]; 
    }
}