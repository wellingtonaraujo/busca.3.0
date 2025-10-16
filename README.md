## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
"# dashboard-tailwincss" 
"# busca.3.0" 


# Nome do Projeto

Descrição breve do seu projeto aqui.

---

# Configuração obrigatória
### Composer versão 2.8.10
### PHP versão 8.4.10
### MySql versão 8.0.30
### PhpMyAdmin versão 5.2.2
### Npm versão 10.9.2

## Primeiros passos

### Versão do PHP
Este projeto utiliza o **PHP 8.4**.  
Certifique-se de que você tem a versão correta instalada antes de iniciar.

### Instalar dependências do Composer
Execute o comando abaixo para atualizar e instalar as dependências do backend:

```bash
composer update

### Instalar dependências do NPM
Para instalar as dependências do frontend, rode:
```bash
npm install --legacy-peer-deps

### Configurando o .env
Mude a chave .env
APP_URL=http://dashboard-tailwincss.desenv
para:
APP_URL=http://sua-aplicacai.desenv
### Também faça as devidas alterações nas chaves de conexão com o banco de dados

### instalar plugin de imagens
composer require intervention/image:^3
php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider" --tag=config

>>> use Intervention\Image\Laravel\Facades\Image;
>>> $p = public_path('assets/images/icons/avatar5.png');
>>> file_exists($p);              // deve retornar true
>>> $uri = Image::read($p)->toDataUri();
>>> substr($uri, 0, 40);          // deve começar com "data:image/png;base64,"

