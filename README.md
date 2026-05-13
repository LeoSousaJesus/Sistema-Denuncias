# 🌿 EcoAlert - Sistema de Denúncias Ambientais

O **EcoAlert** é um sistema web responsivo projetado para facilitar o registro, acompanhamento e a gestão de ocorrências ambientais, como focos de incêndio, desmatamento e descarte irregular de resíduos. Desenvolvido com foco na usabilidade, o sistema oferece uma interface humanizada, limpa e altamente interativa.

---

## 🚀 Principais Funcionalidades

### Para o Cidadão (Público)
*   **Formulário de Denúncia Simplificado:** Permite enviar detalhes da ocorrência anonimamente.
*   **Geocodificação Reversa Automática:** Obtém o nome da rua e cidade em tempo real através da API Nominatim com base na posição do usuário (GPS).
*   **Mapa Interativo Integrado:** Caso o usuário não possua GPS, é possível abrir um mapa do *OpenStreetMap* dentro do próprio formulário, clicar no local exato da ocorrência, e o sistema preencherá o endereço automaticamente.
*   **Upload de Evidências:** Permite anexar imagens (`.jpg`, `.png`, `.webp`) à denúncia.
*   **Acompanhamento via Protocolo:** Após a submissão, a aplicação gera um protocolo único (Ex: `DEN-2026-1234`) para que o usuário possa consultar o andamento (Status) da resolução posteriormente.
*   **Mapa Público de Ocorrências:** Uma visão geral de todas as denúncias registradas na região, exibindo marcadores interativos com os protocolos, endereços e os status atuais.

### Para a Fiscalização (Painel Administrativo)
*   **Acesso Restrito:** Área protegida por senha, acessível apenas para servidores.
*   **CRUD Completo:** 
    *   **Ler/Visualizar:** Listagem em formato de tabela contendo todas as denúncias, data, imagem em anexo, endereço textual e botão para abrir as coordenadas direto no Google Maps.
    *   **Atualizar Status:** Controle de fluxo da ocorrência (Recebida -> Em Análise -> Fiscalização Enviada -> Resolvida -> Descartada).
    *   **Excluir:** Opção para deletar registros falsos ou testes. O sistema limpa automaticamente o banco de dados e remove os arquivos de imagem associados do servidor, poupando armazenamento.

---

## 🛠️ Tecnologias Utilizadas

Este projeto foi construído utilizando tecnologias consolidadas de mercado, evitando *frameworks* pesados na camada visual para priorizar a performance e a customização nativa.

*   **Backend:** PHP (Vanilla)
*   **Banco de Dados:** MySQL (Consultas parametrizadas utilizando `mysqli`)
*   **Frontend (Estrutura):** HTML5 Semântico
*   **Frontend (Estilização):** CSS3 Vanilla (Utilizando variáveis CSS, Flexbox e Design System próprio focado em UI limpa, cores sustentáveis e responsividade).
*   **Frontend (Interatividade):** JavaScript Vanilla (ECMAScript 6+)
*   **Mapas e Geodados:**
    *   **Leaflet.js:** Biblioteca open-source para mapas interativos web.
    *   **OpenStreetMap Tiles:** Provedor do visual dos mapas.
    *   **Nominatim API:** Utilizado para *Reverse Geocoding* via `fetch` assíncrono (transformação de latitude/longitude em endereços textuais legíveis).
*   **Arquitetura:** Padrão PRG (Post/Redirect/Get) implementado para evitar submissões duplicadas de formulários e melhorar a experiência e segurança do usuário.

---

## 📂 Estrutura do Projeto

```text
sistema-denuncias/
├── admin/
│   ├── login.php          # Tela de autenticação dos servidores
│   ├── logout.php         # Destruição de sessão
│   └── painel.php         # Painel CRUD administrativo
├── assets/
│   └── css/
│       └── style.css      # Design System global da aplicação
├── banco/
│   └── db_sistema_denuncia.sql  # Script de criação das tabelas e do banco
├── includes/
│   ├── db.php             # Configuração da conexão PDO/MySQLi
│   ├── header.php         # Componente global: Cabeçalho
│   └── footer.php         # Componente global: Rodapé
├── process/
│   └── salvar_denuncia.php# Script Backend de processamento, upload e PRG
├── uploads/               # Diretório onde as imagens das denúncias são salvas
├── denuncia.php           # Página do formulário de nova ocorrência
├── index.php              # Landing Page / Página inicial
├── mapa.php               # Mapa global de ocorrências cadastradas
├── status.php             # Página de busca de protocolo
├── sucesso.php            # Página de confirmação padronizada de envio
└── README.md              # Documentação do projeto
```

---

## ⚙️ Como Instalar e Rodar Localmente (Desenvolvimento)

1. **Pré-requisitos:** Você precisará de um servidor local que suporte PHP e MySQL (recomenda-se o **XAMPP**, WAMP ou Laragon).
2. **Clonar o Repositório:** Coloque a pasta do projeto dentro da pasta de execução pública do seu servidor web (ex: `htdocs` no XAMPP ou `www` no WAMP).
3. **Configurar o Banco de Dados:**
   * Abra o phpMyAdmin (`http://localhost/phpmyadmin`).
   * Crie um banco de dados vazio chamado `sistema_denuncias` (opcional, pois o script faz isso).
   * Importe o arquivo `/banco/db_sistema_denuncia.sql` ou cole o conteúdo na aba SQL para gerar a tabela `denuncias`.
4. **Verificar a Conexão:** Acesse `includes/db.php` e garanta que as credenciais (host, user, password, dbname) estão idênticas às do seu servidor local.
5. **Pronto!** Acesse pelo navegador: `http://localhost/sistema-denuncias/`.

*Login padrão do Administrador:*
*   **Usuário:** admin
*   **Senha:** Ecoalert123

---

## ✨ Princípios de UX/UI Aplicados

*   **Human-Centric:** Linguagem acolhedora nas respostas de sucesso e erro.
*   **Minimalismo Sustentável:** Fuga da estética agressiva/neon, adotando paletas com tons de verde folha (`#2E7D32`), beges naturais (`#F4F1EA`) e brancos puros, evocando as temáticas de ecologia e limpeza.
*   **Acessibilidade e Feedback:** Uso de emojis descritivos, fontes legíveis (Google *Inter*), e feedback de ações claro (Avisos centralizados e modais coloridos correspondentes ao status).
