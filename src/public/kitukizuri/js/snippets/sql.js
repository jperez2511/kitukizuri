var SQL = {
    createDependencyProposals: function (range){
        return [
            {
                label          : '"create table"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'The CREATE TABLE statement is used to create a new table in a database.',
                insertText     : 'CREATE TABLE ${1:table_name} ( \n ${2:column1} ${3:datatype} \n);',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },
            {
                label          : '"create table if not exist"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'The CREATE TABLE statement is used to create a new table in a database.',
                insertText     : 'CREATE TABLE ${1:table_name} ( \n ${2:column1} ${3:datatype} \n);',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            },

        ]; 
    }
}