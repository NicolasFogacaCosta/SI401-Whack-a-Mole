# Whack-a-Mole - SI401

Jogo web de acertar toupeiras (*Whack-a-Mole*) com cadastro de usuários, login, partidas configuráveis, histórico e ranking. Projeto em grupo desenvolvido para a disciplina **SI401 - Programação para a Web**.

**Autores:** _[nomes e RAs do grupo]_

---

## 🎮 Sobre o jogo

- Duas modalidades: **Clássica** (só toupeiras) e **Explosiva** (toupeiras e bombas; bomba acertada tira ponto).
- Tabuleiro quadrado de **2×2 até 8×8** buracos.
- Cada nível dura 15 segundos e exige **90% de acertos** para avançar. A cada nível, as toupeiras ficam menos tempo visíveis.
- A lógica do jogo roda no navegador (JavaScript). O PHP cuida de cadastro, login, sessão e banco de dados.

---

## 🏛️ Arquitetura (MVC simples em PHP puro)

O projeto está sendo refatorado para o padrão **MVC (Model-View-Controller)**, sem frameworks, sem Composer e sem *router*. A ideia é manter tudo simples para o grupo: **uma tela = um controller + uma view** (e um model quando a tela usa o banco).

### Quem faz o quê

| Camada | Pasta | Responsabilidade | O que **não** pode ter |
|---|---|---|---|
| **Model** | `app/model/` | Conversa com o banco de dados (SQL, PDO). Funções que recebem `$pdo` e retornam dados. | `echo`, `header()`, HTML |
| **View** | `app/view/` | Só HTML e `<?= $variavel ?>` para exibir dados. | SQL, regras de negócio |
| **Controller** | `app/controller/` | Recebe a requisição (`$_POST`, `$_GET`, sessão), chama o Model e escolhe a View. É o arquivo que o navegador acessa. | HTML de tela, SQL |

### Caminho de uma requisição

```text
Navegador ──► controller/cadastro.php
                 │
                 ├─ GET  → inclui view/cadastro.php (mostra o formulário)
                 │
                 └─ POST → chama model/usuario.php (cadastrarUsuario)
                           └─► redireciona (header Location)
```

A View nunca é acessada diretamente: ela é sempre incluída por um controller (ou, no caso do login, pelo `public/index.php`).

### Estrutura de pastas

```text
SI401-Whack-a-Mole/
├── app/
│   ├── config/
│   │   └── database.php          # Conexão PDO com o MySQL
│   ├── controller/
│   │   ├── cadastro.php          # Cria conta (GET: formulário / POST: grava)
│   │   ├── login.php             # Valida usuário e senha, abre a sessão
│   │   ├── logout.php            # Destrói a sessão
│   │   ├── menu.php              # (pendente) inclui view/menu.php
│   │   ├── jogo.php              # (pendente) tela de nova partida
│   │   ├── partida.php           # (pendente) tela da partida
│   │   ├── fim.php               # (pendente) fim de partida
│   │   ├── historico.php         # (pendente) histórico do jogador
│   │   ├── ranking.php           # (pendente) ranking global
│   │   └── edicao.php            # (pendente) edição de cadastro
│   ├── model/
│   │   ├── usuario.php           # cadastrarUsuario(), loginUsuario()
│   │   └── partida.php           # (pendente) salvar partida, histórico, ranking
│   └── view/
│       ├── login.php             # Formulário de login (incluído pelo public/index.php)
│       ├── cadastro.php
│       ├── menu.php
│       ├── jogo.php
│       ├── partida.php
│       ├── fim.php
│       ├── historico.php
│       ├── ranking.php
│       └── edicao.php
├── database/
│   └── script.php                # Cria as tabelas do banco
├── public/                       # Ponto de entrada e arquivos estáticos
│   ├── index.php                 # Página inicial: estrutura HTML + require da view de login
│   └── assets/
│       ├── css/                  # style.css (compartilhado) + um CSS por tela
│       ├── img/                  # Imagens e favicon
│       └── js/                   # configuracao.js, partida.js, fim.js
└── README.md
```

### Detalhes importantes sobre caminhos

- **Views incluídas por controllers** (todas, exceto o login) ficam, para o navegador, em `app/controller/`. Por isso os caminhos de CSS, imagens e JS são `../../public/assets/...` e os links entre telas usam só o nome do controller (`menu.php`, `jogo.php`...).
- **A view `login.php` é exceção:** ela só deve ser incluída pelo `public/index.php`, que mantém a estrutura HTML (`<head>`, CSS, `<body>`). Por isso os caminhos dela são relativos a `public/` (`assets/...`). Se for incluída por outro lugar, o CSS e as imagens quebram.
- **Erro de login:** o controller de login redireciona de volta para `public/index.php?erro=1`, e o `index.php` define a variável `$erro` usada pela view.

### Regra de ouro para o grupo

> Se um arquivo tem SQL, ele é **model**. Se tem HTML, é **view**. Se decide para onde ir e o que mostrar, é **controller**.

---

## 🚀 Como executar (XAMPP)

1. Coloque a pasta do projeto em `htdocs` (no Linux, por exemplo: `/opt/lampp/htdocs/SI401-Whack-a-Mole`).
2. Inicie o **Apache** e o **MySQL** pelo painel do XAMPP.
3. No phpMyAdmin, crie o banco de dados `whack_a_mole` (collation `utf8mb4_general_ci` ou similar).
4. Acesse `http://localhost/SI401-Whack-a-Mole/database/script.php` uma vez para criar as tabelas (`usuarios` e `partidas`).
5. Acesse `http://localhost/SI401-Whack-a-Mole/public/index.php`.

A conexão usa os dados padrão do XAMPP (`127.0.0.1`, usuário `root`, senha vazia), definidos em `app/config/database.php`.

---

## ➕ Como adicionar uma nova tela ou funcionalidade

Sem router e sem alterar arquivos existentes, o passo a passo é sempre o mesmo:

1. **View:** crie `app/view/minha_tela.php` só com HTML (e variáveis vindas do controller).
2. **Model** (se usar banco): crie ou amplie `app/model/algo.php` com funções que recebem `$pdo`.
3. **Controller:** crie `app/controller/minha_tela.php`, que:
   - faz `require_once` do `database.php` e do model necessários;
   - trata `$_POST` ou `$_GET`, se houver;
   - termina com `require __DIR__ . '/../view/minha_tela.php';` (ou com um `header('Location: ...')` + `exit`).
4. **Link:** na tela de origem, aponte o `href` para o nome do controller (`minha_tela.php`).

Exemplo mínimo de controller sem banco:

```php
<?php
require __DIR__ . '/../view/menu.php';
```

---

## 🧭 Status da refatoração para MVC

### ✅ Já adequado à nova arquitetura

- [x] Estrutura de pastas `app/config`, `app/controller`, `app/model`, `app/view`.
- [x] `model/usuario.php` com `cadastrarUsuario()` e `loginUsuario()`.
- [x] Controllers de **cadastro**, **login** e **logout**.
- [x] `public/index.php` mantendo a estrutura HTML e incluindo `view/login.php`.
- [x] Todas as telas convertidas de `.html` para `.php` em `app/view/`.
- [x] Links e redirecionamentos em JS (`configuracao.js`, `partida.js`) apontando para `.php`.
- [x] Pasta `app/actions/` substituída pelos controllers.

### 🔧 O que ainda falta

**Controllers que ainda não existem** (as telas hoje navegam de view para view, sem passar por controller):

- [ ] `controller/menu.php`
- [ ] `controller/jogo.php`
- [ ] `controller/partida.php`
- [ ] `controller/fim.php`
- [ ] `controller/historico.php`
- [ ] `controller/ranking.php`
- [ ] `controller/edicao.php`

**Telas que ainda usam dados fixos e precisam vir do banco:**

- [ ] **Histórico:** buscar as partidas do usuário logado (`model/partida.php`) e exibir com `foreach` na view. Hoje a tabela e o nome do jogador estão escritos à mão.
- [ ] **Ranking:** consulta com o total de toupeiras acertadas por jogador. Hoje os 8 jogadores são fixos.
- [ ] **Edição de cadastro:** buscar os dados do usuário logado e preencher o formulário; criar a função de atualização no model. Hoje os campos têm valores de exemplo.
- [ ] **Salvar partida:** ao fim do jogo, gravar o resultado na tabela `partidas`. Hoje o `partida.js` guarda o resultado só no `sessionStorage`, e o `fim.js` apenas o lê. Isso exige um controller que receba o resultado (por exemplo via `POST`) e uma função no model.

**Sessão e acabamento:**

- [ ] Proteger as telas que exigem login (verificar `$_SESSION['usuario_id']` no início dos controllers, redirecionando para `public/index.php`). Fazer apenas se o enunciado pedir.
- [ ] Trocar o `<p id="form-error" ... hidden>` das views de **cadastro** e **edição** pelo bloco condicional `<?php if (!empty($erro)): ?>`, como já feito no login.
- [ ] Tratar CPF, e-mail ou usuário repetidos no cadastro (hoje uma duplicidade pode causar erro 500).
- [ ] Remover do repositório arquivos antigos que sobraram (`*.html` das views, `app/actions/`, se ainda existirem).

**Organização e configuração:**

- [ ] Centralizar as credenciais do banco: `database/script.php` ainda tem a própria conexão, separada de `app/config/database.php`.
- [ ] Remover o `echo` que vem depois do `throw` em `database.php` (código que nunca executa).
- [ ] Reavaliar a proteção da pasta `app/` (hoje acessível pelo navegador, como no restante do projeto). Aceitável para fins acadêmicos, mas não para produção.

---

## 📐 Convenções para o grupo

- **Dividir o trabalho por tela**, não por camada: cada pessoa cria os arquivos de view, controller e model da sua tela. Assim, ninguém edita o mesmo arquivo ao mesmo tempo.
- `public/assets/css/style.css` é **compartilhado**: avise o grupo antes de alterar.
- Nunca usar SQL fora de `app/model/`.
- Usar sempre `htmlspecialchars()` ao exibir na view qualquer texto vindo do usuário ou do banco.
- Testar o fluxo completo depois de cada mudança: login → menu → jogo → partida → fim → histórico → ranking → logout.

---

## 🛠️ Tecnologias

- **Backend:** PHP 8+ (PDO)
- **Banco de dados:** MySQL / MariaDB (XAMPP + phpMyAdmin)
- **Frontend:** HTML5, CSS3 e JavaScript puro
- **Ambiente:** XAMPP (Apache 2.4 + PHP 8.2)
