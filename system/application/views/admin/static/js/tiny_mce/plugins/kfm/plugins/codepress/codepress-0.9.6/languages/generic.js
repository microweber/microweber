
Language.syntax=[{input:/\"(.*?)(\"|<br>|<\/P>)/g,output:'<s>"$1$2</s>'},{input:/\'(.*?)(\'|<br>|<\/P>)/g,output:'<s>\'$1$2</s>'},{input:/\b(abstract|continue|for|new|switch|default|goto|boolean|do|if|private|this|break|double|protected|throw|byte|else|import|public|throws|case|return|catch|extends|int|short|try|char|final|interface|static|void|class|finally|long|const|float|while|function|label)\b/g,output:'<b>$1</b>'},{input:/([\(\){}])/g,output:'<em>$1</em>'},{input:/([^:]|^)\/\/(.*?)(<br|<\/P)/g,output:'$1<i>//$2</i>$3'},{input:/\/\*(.*?)\*\//g,output:'<i>/*$1*/</i>'}]
Language.snippets=[]
Language.complete=[{input:'\'',output:'\'$0\''},{input:'"',output:'"$0"'},{input:'(',output:'\($0\)'},{input:'[',output:'\[$0\]'},{input:'{',output:'{\n\t$0\n}'}]
Language.shortcuts=[]