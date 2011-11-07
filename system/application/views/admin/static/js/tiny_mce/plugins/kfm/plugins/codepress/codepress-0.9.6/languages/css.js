
Language.syntax=[{input:/(.*?){(.*?)}/g,output:'<b>$1</b>{<u>$2</u>}'},{input:/([\w-]*?):([^\/])/g,output:'<a>$1</a>:$2'},{input:/\((.*?)\)/g,output:'(<s>$1</s>)'},{input:/\/\*(.*?)\*\//g,output:'<i>/*$1*/</i>'}]
Language.snippets=[]
Language.complete=[{input:'\'',output:'\'$0\''},{input:'"',output:'"$0"'},{input:'(',output:'\($0\)'},{input:'[',output:'\[$0\]'},{input:'{',output:'{\n\t$0\n}'}]
Language.shortcuts=[]