<?php /* Smarty version 2.6.0, created on 2010-04-19 01:01:55
         compiled from classtrees.tpl */ ?>
<?php ob_start(); ?>Class Trees for Package <?php echo $this->_tpl_vars['package'];  $this->_smarty_vars['capture']['title'] = ob_get_contents(); ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('title' => $this->_smarty_vars['capture']['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- Start of Class Data -->
<H2>
	<?php echo $this->_smarty_vars['capture']['title']; ?>

</H2>
<?php if (isset($this->_sections['classtrees'])) unset($this->_sections['classtrees']);
$this->_sections['classtrees']['name'] = 'classtrees';
$this->_sections['classtrees']['loop'] = is_array($_loop=$this->_tpl_vars['classtrees']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['classtrees']['show'] = true;
$this->_sections['classtrees']['max'] = $this->_sections['classtrees']['loop'];
$this->_sections['classtrees']['step'] = 1;
$this->_sections['classtrees']['start'] = $this->_sections['classtrees']['step'] > 0 ? 0 : $this->_sections['classtrees']['loop']-1;
if ($this->_sections['classtrees']['show']) {
    $this->_sections['classtrees']['total'] = $this->_sections['classtrees']['loop'];
    if ($this->_sections['classtrees']['total'] == 0)
        $this->_sections['classtrees']['show'] = false;
} else
    $this->_sections['classtrees']['total'] = 0;
if ($this->_sections['classtrees']['show']):

            for ($this->_sections['classtrees']['index'] = $this->_sections['classtrees']['start'], $this->_sections['classtrees']['iteration'] = 1;
                 $this->_sections['classtrees']['iteration'] <= $this->_sections['classtrees']['total'];
                 $this->_sections['classtrees']['index'] += $this->_sections['classtrees']['step'], $this->_sections['classtrees']['iteration']++):
$this->_sections['classtrees']['rownum'] = $this->_sections['classtrees']['iteration'];
$this->_sections['classtrees']['index_prev'] = $this->_sections['classtrees']['index'] - $this->_sections['classtrees']['step'];
$this->_sections['classtrees']['index_next'] = $this->_sections['classtrees']['index'] + $this->_sections['classtrees']['step'];
$this->_sections['classtrees']['first']      = ($this->_sections['classtrees']['iteration'] == 1);
$this->_sections['classtrees']['last']       = ($this->_sections['classtrees']['iteration'] == $this->_sections['classtrees']['total']);
?>
<SPAN class="code">Root class <?php echo $this->_tpl_vars['classtrees'][$this->_sections['classtrees']['index']]['class']; ?>
</SPAN>
<code class="vardefaultsummary"><?php echo $this->_tpl_vars['classtrees'][$this->_sections['classtrees']['index']]['class_tree']; ?>
</code>
<?php endfor; endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>