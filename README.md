# Sistema de Cadastro de Contatos

Este projeto foi desenvolvido como parte de um teste técnico para avaliação de habilidades em desenvolvimento backend utilizando PHP e o framework Laravel. Ele implementa um sistema de gerenciamento de contatos com integração ao Via Cep e Google Maps.

## Funcionalidades

- Cadastro de usuário e autenticação:
  - Registro com validação de e-mail único.
  - Recuperação de senha por e-mail.
  - Login e logout seguros.

- Gerenciamento de contatos:
  - Adição de contatos com validação de CPF e preenchimento automático de endereço via integração com a API do Via Cep.
  - Atualização e exclusão de contatos.
  - Filtro de pesquisa de contatos por nome ou CPF.
  - Exibição da localização do contato no Google Maps.

- Gerenciamento de conta:
  - Exclusão da conta do usuário, removendo todos os dados associados.

## Tecnologias Utilizadas

- **Backend**: PHP com Laravel
- **Banco de Dados**: Banco de dados relacional (MySQL ou PostgreSQL)
- **APIs Externas**:
  - Via Cep para busca de endereços.
  - Google Maps para geolocalização.
- **Versionamento de Código**: Git

## Requisitos de Instalação

1. PHP >= 7.4
2. Composer
3. Banco de Dados Relacional (MySQL ou PostgreSQL)
4. Chave de API do Google Maps

# Rodar Localmente o Backend e Front-End
Consulte o README de cada monorepo
