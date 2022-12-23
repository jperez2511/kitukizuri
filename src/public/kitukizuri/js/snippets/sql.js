var SQL = {
    createDependencyProposals: function (range){
        return [
            {
                label          : '"where"',
                kind           : monaco.languages.CompletionItemKind.Function,
                documentation  : 'The most basic call to the where method requires three arguments. The first argument is the name of the column. The second argument is an operator, which can be any of the database\'s supported operators. The third argument is the value to compare against the column\'s value.',
                insertText     : 'where(\'${1:votes}\', \'${2:=}\', ${3:100})',
                insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                range          : range
            }
        ]; 
    }
}