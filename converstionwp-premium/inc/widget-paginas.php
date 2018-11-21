<?php
/**
 * Widget Clique Páginas
 * 
 * @author Jair Rebello <jair@rebello.blog.br>
 */
class cliquePaginas extends WP_Widget {
    /**
     * Construtor
     */
    public function cliquePaginas() {
        parent::__construct(false, $name = 'Inserir Página');
    }

    /**
     * Exibição final do Widget (já no sidebar)
     *
     * @param array $argumentos Argumentos passados para o widget
     * @param array $instancia Instância do widget
     */
    public function widget($argumentos, $instancia) {
        wp_reset_postdata();
        $p = $instancia['select-pages'];
        $query = new WP_Query('page_id=' . $p);
        if ($query->have_posts()){
            while ( $query->have_posts()){
                $query->the_post();  
                echo $argumentos['before_widget'];
                echo '<div class="widget-paginas">';
                if ($instancia['destaque'] && $instancia['cor_1'] == '')
                    $class = 'destaque';
                else
                    $class = "no-destaque";
                if ($instancia['cor_1'] <> '') 
                    echo '<style>#' . $argumentos['widget_id'] . '{background: ' . $instancia['cor_1'] . '!important;}</style>';
                if (isset($instancia['cor_2'])) 
                    echo '<style>#' . $argumentos['widget_id'] . ' a {border-color: ' . $instancia['cor_2'] . '!important;}'
                        . ' #' . $argumentos['widget_id'] . ' a:hover {background: ' . $instancia['cor_2'] . '!important;}</style>';
                if (isset($instancia['cor_3'])) 
                    echo '<style>#' . $argumentos['widget_id'] . ' a {color: ' . $instancia['cor_3'] . '!important;}</style>';
                if (isset($instancia['titulo']) && $instancia['titulo'] <> '')
                    $titulo = $instancia['titulo'];
                else
                    $titulo = get_the_title();
                echo '<a class="' . $class . '" href="' . get_permalink() . '">' . '<i class="fa ' . $instancia['icone-fa'] . '"></i><div>' 
                        . $titulo .  '</div></a>';
                echo '</div>';
                echo $argumentos['after_widget'];
            }
        }
    }

    /**
     * Salva os dados do widget no banco de dados
     *
     * @param array $nova_instancia Os novos dados do widget (a serem salvos)
     * @param array $instancia_antiga Os dados antigos do widget
     * 
     * @return array $instancia Dados atualizados a serem salvos no banco de dados
     */
    public function update($nova_instancia, $instancia_antiga) {
        $instancia = array_merge($instancia_antiga, $nova_instancia);
        return $instancia;
    }

    /**
     * Formulário para os dados do widget (exibido no painel de controle)
     *
     * @param array $instancia Instância do widget
     */
    public function form($instancia) {
        
        ?>
        <script>
            var j = jQuery.noConflict(); 
            j( '.odin-color-field' ).wpColorPicker();
            j(".edit-menu-item-attr-title, #icone, .geticon").click(function() {
                j("a.inline").trigger('click');
                idedit = j(this).attr('id');
                console.log(idedit);
            });
            j("a.inline").fancybox({
                type: "inline",
                width: "800",
                height: "350",
                autoScale: false,
                autoDimensions: false
            });
        </script>
        <p>Página: <select id="select-pages" name="<?php echo $this->get_field_name('select-pages'); ?>">
            <?php
            $pages = get_pages();
            foreach ($pages as $p) {
                $selected = '';
                if ($p->ID == $instancia['select-pages'])
                    $selected = 'selected';
                echo '<option ' . $selected . ' value="' . $p->ID . '">' . $p->post_title . '</option>';
            }
            ?>
            </select></p>
            <p>Título <span>(Coloque apenas se quiser um título diferente do título da página)</span>: <input class="titulo-top-artigos" name="<?php echo $this->get_field_name('titulo'); ?>" id="<?php echo $this->get_field_id('titulo'); ?>" type="text" value="<?php echo $instancia['titulo']; ?>"/></p>
            
        <div><a id="a-<?php echo $this->get_field_id('icone-fa'); ?>" class="geticon" href="#data"><i class="fa  <?php echo $instancia['icone-fa'];?> escolher-icone"></i>Escolher ícone</a></div>
        <p><input name="<?php echo $this->get_field_name('icone-fa'); ?>" id="<?php echo $this->get_field_id('icone-fa'); ?>" type="hidden" value="<?php echo $instancia['icone-fa'];?>"/></p>
        <?php 
            $widget['destaque'] = (boolean)$instancia['destaque'];
            if ($widget['destaque'])
                $value = '1';
            else
                $value = '0';
        ?>
        <p>Destacar?
            <select name="<?php echo $this->get_field_name('destaque'); ?>" id="<?php echo $this->get_field_id('destaque'); ?>">
                <option value="1" <?php if($value == '1') echo 'selected'; ?>>Sim</option>
                <option value="0" <?php if($value == '0') echo 'selected'; ?>>Não</option>
            </select>
        </p>
        
        <p>Cor Principal <span>(Coloque apenas se quiser a cor seja diferente do padrão do template)</span>: </p>
        <p> <input class="titulo-top-artigos odin-color-field" name="<?php echo $this->get_field_name('cor_1'); ?>" 
                   id="<?php echo $this->get_field_id('cor_1'); ?>" type="text" 
                   value="<?php echo $instancia['cor_1']; ?>"/>
        </p>
        
        <p>Cor da Borda <span>(Coloque apenas se quiser a cor seja diferente do padrão do template)</span>: </p>
        <p> <input class="titulo-top-artigos odin-color-field" name="<?php echo $this->get_field_name('cor_2'); ?>" 
                   id="<?php echo $this->get_field_id('cor_2'); ?>" type="text" 
                   value="<?php echo $instancia['cor_2']; ?>"/>
        </p>
        
        <p>Cor do Texto <span>(Coloque apenas se quiser a cor seja diferente do padrão do template)</span>: </p>
        <p> <input class="titulo-top-artigos odin-color-field" name="<?php echo $this->get_field_name('cor_3'); ?>" 
                   id="<?php echo $this->get_field_id('cor_3'); ?>" type="text" 
                   value="<?php echo $instancia['cor_3']; ?>"/>
        </p>
        
        <?php
    }

}

add_action('widgets_init', create_function('', 'return register_widget("cliquePaginas");'));
?>