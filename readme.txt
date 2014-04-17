=== Social FBlog ===
Contributors: claudiosanches
Donate link: http://claudiosmweb.com/doacoes/
Tags: share, jquery, facebook, twitter, google plus, linkedin, pinterest, email
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 3.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Inserts a floating box next to your blog posts to share your content on Twitter, Facebook and Google Plus and others.

== Description ==

Inserts a floating box a floating box with buttons to share your posts on social networks easily.
With a clean and lightweight plugin.

### Descrição em Português: ###

Adicione uma caixa flutuante com botões para compartilhar seus posts em redes sociais facilmente.
Com um plugin limpo e leve.

== Installation ==

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;
* Navigate to Settings -> Social FBlog and fill the plugin options.

### Instalação em Português: ###

* Envie arquivos do plugin para a pasta de plugins, ou instale usando o o instalador do WordPress em Plugins -> Adicionar Novo;
* Ative o plugin;
* Navegue até Configurações -> Social FBlog e preencha as opções do plugin.

== Frequently Asked Questions ==

= What is the plugin license? =

* This plugin is released under a GPL license.

= Why the plugin is not working? =

* Probably due to incompatibility of jQuery. Check to see if this plugin modifying the jQuery version of your WordPress.

= The Facebook sharing this wrong images! =

* The Facebook randomly chooses images in your layout. To fix this I suggest you use the [WP Open Graph](http://wordpress.org/extend/plugins/wp-open-graph/) plugin.

= Can I add new buttons? =

Yep!

Use the filters for this:

* `socialfblog_scripts` - Add new scripts.
* `socialfblog_styles` - Add new styles.
* `socialfblog_buttons` - Add new buttons in HTML.

**Example of use with the [Buffer](http://bufferapp.com) button:**

	function socialfblog_add_buffer_html( $html ) {

		// Gets post data.
		global $post;

		// Adds the new button.
		$html .= sprintf( '<a href="http://bufferapp.com/add" class="buffer-add-button" data-text="%s" data-url="%s" data-count="vertical" data-via="ferramentasblog">Buffer</a>', $post->post_title, get_permalink( $post->ID ) );

		return $html;

	}

	add_filter( 'socialfblog_buttons', 'socialfblog_add_buffer_html' );

	function socialfblog_add_buffer_scripts( $scripts ) {

		// Adds the new scripts.
		$scripts .= '<script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>';

		return $scripts;
	}

	add_filter( 'socialfblog_scripts', 'socialfblog_add_buffer_scripts' );

The code should be added to the `functions.php` of your theme.

### FAQ em Português: ###

= Qual é a licença do plugin? =

* Este plugin esta licenciado como GPL.

= Instalei o plugin, mas ele não esta funcionando, o que pode ser? =

* Provavelmente por incompatibilidade do jQuery. Verifique se algum plugin esta modificando a versão do jQuery do seu WordPress.

= O Facebook esta compartilhando imagens erradas! =

* O Facebook escolhe aleatoriamente imagens em seu layout. Para corrigir isso sugiro você usar o plugin [WP Open Graph](http://wordpress.org/extend/plugins/wp-open-graph/).

= Posso adicionar novos botões? =

Sim!

Para isso utilize os filtros:

* `socialfblog_scripts` - Serve para adicionar novos scripts.
* `socialfblog_styles` - Serve para adicionar novos estilos.
* `socialfblog_buttons` - Serve para adicionar o HTML dos novos botões.

**Exemplo de uso com o botão do [Buffer](http://bufferapp.com):**

	function socialfblog_add_buffer_html( $html ) {

		// Pega as informações do post.
		global $post;

		// Adiciona o novo botão.
		$html .= sprintf( '<a href="http://bufferapp.com/add" class="buffer-add-button" data-text="%s" data-url="%s" data-count="vertical" data-via="ferramentasblog">Buffer</a>', $post->post_title, get_permalink( $post->ID ) );

		return $html;

	}

	add_filter( 'socialfblog_buttons', 'socialfblog_add_buffer_html' );

	function socialfblog_add_buffer_scripts( $scripts ) {

		// Adiciona o novo javascript.
		$scripts .= '<script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>';

		return $scripts;
	}

	add_filter( 'socialfblog_scripts', 'socialfblog_add_buffer_scripts' );

O código deve ser adicionado no `functions.php` do seu tema.

== Screenshots ==

1. Plugin buttons options.
2. Plugin settings.
3. Plugin in action.

== Changelog ==

= 3.2.0 - 13/12/2013 =

* Fixed standards.
* Improved support do WordPress 3.8.

= 3.1.0 - 08/11/2013 =

* Refactored all code.
* Improved the JavaScripts.
* Improved the styles.

= 3.0.0 - 03/01/2013 =

* Source code reformulation.
* Added LinkedIn button.
* Added Pinterest button.
* Added Email button.
* Removed the option to add extra buttons sharing.
* Added filters to add new buttons for sharing.
* Improved performance with fewer options in the database.
* Added Brazilian Portuguese and English languages.

== License ==

Social FBlog is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Social FBlog is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Author Bio Box. If not, see <http://www.gnu.org/licenses/>.
