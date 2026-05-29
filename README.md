![logo_text.png](https://server.hiosaki.com.br/index.php?explorer/share/file&hash=ed7aXkjiZySbZ6p9a3UEBxAhvUM5Q8bkDRfEqw2mhcTb0qlPdGxRB227LjHLnZ9FuvedRNmmOmVzQWE0aurcs-fpBWNUrbN8nvNStTqc9XsOTE_ydW-3r8Ph&name=/logo_text.png)


# Sistema de vendas com banco de dados `MySQL`


Sistema simples em PHP puro + MySQL para acompanhar seu sistema de vendas com compras via **WhatsApp**

## Requisitos
- PHP 7.4+ (recomendado 8.x) com extensões `pdo_mysql` e `curl`
- MySQL 5.7+ / MariaDB 10+
- Servidor web (Apache, Nginx ou simplesmente `php -S`)

## Instalação

1. **Crie o banco** importando `db.sql` no seu MySQL:
   ```bash
   mysql -u root -p < db.sql
   ```
   Isso cria o banco `ead_colaboradores`, as tabelas e um usuário admin
   padrão: **admin / admin123** (troque depois!).

2. **Configure as credenciais** em `config.php`:
   - `DB_HOST`, `DB_USER`, `DB_PASS` do seu MySQL
   - `TWILIO_SID`, `TWILIO_TOKEN`, `TWILIO_FROM_WHATSAPP` (conta em https://twilio.com)
   - `MAIL_FROM` para o e-mail remetente

3. **Suba os arquivos** no seu servidor web (htdocs / public_html).
   Para testar localmente:
   ```bash
   php -S localhost:80
   ```
   Acesse http://localhost:80

4. **/api/** a pasta api é onde fica o `config.php` leia a documentação com ateção para integrar ao seu sistema.


## Funcionalidades
- index de visualização para clientes
- Dashboad adminitrativo via api com acesso a banco de dados
- sistema /Dashboad/tema para edição basica do appweb
- upload local das fotos

## Sobre o envio
- **WhatsApp**: usa a input button.
  Em produção, autere o template de mensagem.

## Segurança — antes de subir em 
- Troque a senha do usuário `admin` (gere novo hash com `password_hash()`).
- Use HTTPS.
- Restrinja o acesso ao diretório (ex.: `.htaccess` ou regra de Nginx).
- Nunca exponha `config.php` na web pública sem proteção.
