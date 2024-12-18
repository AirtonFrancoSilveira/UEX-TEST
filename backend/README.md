# Projeto de Backend - Laravel com JWT

Este projeto é um backend desenvolvido em Laravel com autenticação JWT, funcionalidades de gerenciamento de contatos e recuperação de senha por e-mail.

---

## **Requisitos**
Certifique-se de ter os seguintes itens instalados em sua máquina:

- PHP >= 8.0
- Composer >= 2.0
- Node.js (opcional, para desenvolvimento frontend, se aplicável)
- Banco de dados SQLite (ou outro configurado no `.env`)
- Servidor SMTP para envio de e-mails (pode usar Gmail, Mailtrap, etc.)
- **API do Google Maps habilitada** (para obter latitude e longitude de endereços)

---

## **Como Configurar o Projeto**

1. **Clone o Repositório**

   ```bash
   git clone <URL_DO_REPOSITORIO>
   cd <NOME_DO_DIRETORIO_CLONADO>
   ```

2. **Instale as Dependências**

   ```bash
   composer install
   ```

3. **Configure o Arquivo `.env`**

   Copie o arquivo `.env.example` para `.env`:

   ```bash
   cp .env.example .env
   ```

   Configure as variáveis no arquivo `.env`:

   - **Banco de Dados:**
     ```env
     DB_CONNECTION=sqlite
     DB_DATABASE=/caminho/para/database.sqlite
     ```
     Crie o arquivo SQLite (caso não exista):
     ```bash
     touch database/database.sqlite
     ```

   - **JWT:**
     ```env
     JWT_SECRET=gerado_pelo_comando_abaixo
     JWT_TTL=60
     ```

   - **Serviço de E-mail:**
     ```env
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.gmail.com
     MAIL_PORT=587
     MAIL_USERNAME=seuemail@gmail.com
     MAIL_PASSWORD=suasenha
     MAIL_ENCRYPTION=tls
     MAIL_FROM_ADDRESS=seuemail@gmail.com
     MAIL_FROM_NAME="Nome do Sistema"
     ```

   - **Google Maps API Key:**
     ```env
     GOOGLE_MAPS_API_KEY=sua_chave_api
     ```

     > **Nota:** Certifique-se de habilitar a API do Google Maps na [Google Cloud Console](https://console.cloud.google.com/) e gerar a chave de API.

4. **Gere a Chave JWT**

   ```bash
   php artisan jwt:secret
   ```

5. **Execute as Migrações**

   ```bash
   php artisan migrate
   ```

6. **Limpe o Cache (opcional, para garantir)**

   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   composer dump-autoload
   ```

7. **Inicie o Servidor de Desenvolvimento**

   ```bash
   php artisan serve
   ```

   O servidor estará disponível em: [http://localhost:8000](http://localhost:8000)

---

## **Funcionalidades Disponíveis**

### **Autenticação**

#### **Registro de Usuário**
- **Endpoint:** `POST /api/register`
- **Body:**
  ```json
  {
      "name": "Nome do Usuário",
      "email": "email@example.com",
      "password": "123456"
  }
  ```

#### **Login de Usuário**
- **Endpoint:** `POST /api/login`
- **Body:**
  ```json
  {
      "email": "email@example.com",
      "password": "123456"
  }
  ```
- **Resposta:** Retorna um token JWT.

#### **Logout de Usuário**
- **Endpoint:** `POST /api/logout`
- **Header:**
  ```
  Authorization: Bearer SEU_TOKEN
  ```

#### **Perfil do Usuário**
- **Endpoint:** `GET /api/profile`
- **Header:**
  ```
  Authorization: Bearer SEU_TOKEN
  ```

### **Recuperação de Senha**

#### **Solicitar Link de Redefinição**
- **Endpoint:** `POST /api/password/email`
- **Body:**
  ```json
  {
      "email": "email@example.com"
  }
  ```

#### **Redefinir Senha**
- **Endpoint:** `POST /api/password/reset`
- **Body:**
  ```json
  {
      "email": "email@example.com",
      "token": "TOKEN_RECEBIDO",
      "password": "nova_senha",
      "password_confirmation": "nova_senha"
  }
  ```

### **Gerenciamento de Contatos**

#### **Criar Contato**
- **Endpoint:** `POST /api/contacts`
- **Body:**
  ```json
  {
      "name": "Nome do Contato",
      "cpf": "12345678900",
      "phone": "(11) 98765-4321",
      "address": {
          "cep": "12345-678",
          "uf": "SP",
          "city": "São Paulo",
          "complement": "Apt 101"
      }
  }
  ```

#### **Listar Contatos**
- **Endpoint:** `GET /api/contacts`
- **Query Params:**
  - `name` (opcional)
  - `cpf` (opcional)
  - `page` (opcional, paginação)

#### **Atualizar Contato**
- **Endpoint:** `PUT /api/contacts/{id}`
- **Body:** Semelhante ao de criação.

#### **Deletar Contato**
- **Endpoint:** `DELETE /api/contacts/{id}`

---

## **Testes**

Para executar os testes:

```bash
php artisan test
```

## **Licença**
Este projeto é licenciado sob a [MIT License](LICENSE).
