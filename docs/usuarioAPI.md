# 📌 Usuário API

**URL Base:**  
`/api/v1/usuario/UsuarioController.php`

---

## 🔹 Listar todos os usuários
**Endpoint:**  
`GET /api/v1/usuario/UsuarioController.php?operacao=getUsuarios`

**Descrição:**  
Retorna todos os usuários cadastrados.

**Parâmetros:**  
Nenhum.

**Exemplo de requisição:**  
```http
GET /api/v1/usuario/UsuarioController.php?operacao=getUsuarios
````

**Exemplo de resposta:**  

```json
{
  "operacao": "getUsuarios",
  "NumMens": null,
  "Mensagem": null,
  "registros": 3,
  "dados": [
    {
      "id_usuario": 2,
      "nu_cpf": "55566677788",
      "nm_usuario": "João",
      "vl_email": "joao@email.com",
      "nm_sobrenome": "Santos",
      "vl_senha": "senha456"
    },
    {
      "id_usuario": 3,
      "nu_cpf": "99988877766",
      "nm_usuario": "Ana",
      "vl_email": "ana@email.com",
      "nm_sobrenome": "Oliveira",
      "vl_senha": "senha789"
    },
    {
      "id_usuario": 1,
      "nu_cpf": "4293446001 ",
      "nm_usuario": "Thalis",
      "vl_email": "thalis.trisch2003@gmail.com",
      "nm_sobrenome": "Trisch",
      "vl_senha": "senha123"
    }
  ]
}
```

## 🔹 Buscar usuário por Id
**Endpoint:**  
`GET /api/v1/usuario/UsuarioController.php?operacao=getUsuarioById&id_usuario={id}`

**Descrição:**  
Retorna os dados de um usuário específico pelo seu id_usuario.

**Parâmetros:**  
id_usuario (int) – obrigatório.

**Exemplo de requisição:**  
```http
GET /api/v1/usuario/UsuarioController.php?operacao=getUsuarioById&id_usuario=2
````
**Exemplo de resposta:**  

```json
{
  "operacao": "getUsuarioById",
  "NumMens": null,
  "Mensagem": null,
  "registros": 1,
  "dados": {
    "id_usuario": 2,
    "nu_cpf": "55566677788",
    "nm_usuario": "João",
    "vl_email": "joao@email.com",
    "nm_sobrenome": "Santos",
    "vl_senha": "senha456"
  }
}
```

## 🔹 Criar novo usuário
**Endpoint:**  
`POST /api/v1/usuario/UsuarioController.php?operacao=newUsuario`

**Descrição:**  
Cadastra um novo usuário no sistema.

**Parâmetros:**  
- nu_cpf (string) – obrigatório.
- nm_usuario (string) – obrigatório.
- nm_sobrenome (string) – obrigatório.
- vl_email (string) – obrigatório.
- vl_senha (string) – obrigatório.

**Exemplo de requisição:**  
```http
POST /api/v1/usuario/UsuarioController.php?operacao=newUsuario

Headers:
Content-Type: application/json
````

**Exemplo de resposta:**  

```json
{
  "nu_cpf": "12345678900",
  "nm_usuario": "Carlos",
  "nm_sobrenome": "Pereira",
  "vl_email": "carlos@email.com",
  "vl_senha": "senhaSegura"
}
```
