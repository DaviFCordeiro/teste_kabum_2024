# teste_kabum_2024
Avaliação prática da KABUM 2024

1. Caso não possua, instalar ultima versão do php
- https://www.php.net/downloads.php
- Criar uma pasta chamada "php" na raiz do disco C:
- Extrair conteudo baixado na pasta criada
- Abrir as variáveis de ambiente, adicionar o caminho até a raiz da pasta "php" criada
anteriormente na variável "Path" (Container de baixo "Variáveis do sistema")
- testar abrindo o cmd e rodando o comando php -v

**CASO ESTEJA USANDO WINDOWS :
- Na raiz da pasta php crianda anteriormente :
- Copiar e colar o arquivo "php.ini-development" no mesmo diretorio e renomear 
para "php.ini"
- No arquivo "php.ini" que acabou de modificar, abrir com o bloco de notas, dar um
find (Ctrl + F) e procurar por "pdo_mysql", remover o ; no inicio da linha
- Fazer o mesmo procurando por ;extension_dir="ext", salvar o arquivo e fechar

2. Usaremos o xampp para criar um servidor de Banco de Dados Local
- caso não possua, baixar o xampp
https://www.apachefriends.org/pt_br/download.html
- Após finalizado o download, abrir o xampp e clicar em "start" no módulo do MYSQL

3. Instalar o MYSQL workbench e criar o banco
- https://dev.mysql.com/downloads/workbench/
- Em "MySQL Connections" clicar no "+"
- Digitar o nome que desejar em "Connection Name" e clicar em "OK"
- Copiar o conteudo do arquivo dbInit.sql na raiz do projeto, colar no workbench e clicar
no Raio amarelo acima de onde digitou o texto
- Para visualizar as mudanças, clique com o botão direito no container
a esquerda de titulo "SCHEMAS" e selecione "Refresh all"

4. Rodar comando na raiz do projeto 
- php -S 127.0.0.1:8000

5. Acessos pré definidos :
- Login : admin
- senha : 12345
