<?php
if(!defined('OSTCLIENTINC') || !$category || !$category->isPublic()) die('Access Denied');
?>
<h1><strong><?php echo $category->getName() ?></strong></h1>
<p>
<?php echo Format::safe_html($category->getDescription()); ?>
</p>
<hr>
<?php
$sql='SELECT faq.faq_id, question, count(attach.file_id) as attachments '
    .' FROM '.FAQ_TABLE.' faq '
    .' LEFT JOIN '.ATTACHMENT_TABLE.' attach
         ON(attach.object_id=faq.faq_id AND attach.type=\'F\' AND attach.inline = 0) '
    .' WHERE faq.ispublished=1 AND faq.category_id='.db_input($category->getId())
    .' GROUP BY faq.faq_id '
    .' ORDER BY question';
if(($res=db_query($sql)) && db_num_rows($res)) {
    echo '
         <h2>'.__('Frequently Asked Questions').'</h2>
         <div id="faq">
            <ol class="list-group">';
    while($row=db_fetch_array($res)) {
        $attachments=$row['attachments']?'<span class="Icon file"></span>':'';
        echo sprintf('
            <li class="list-group-item"><i class="fa fa-file-text-o"></i>  <a href="faq.php?id=%d" >%s &nbsp;%s</a></li>',
            $row['faq_id'],Format::htmlchars($row['question']), $attachments);
    }
    echo '  </ol>
         </div>
         <p><a class="back btn btn-primary" href="index.php">&laquo; '.__('Go Back').'</a></p>';
}else {
    echo '<strong>'.__('This category does not have any FAQs.').' <a href="index.php">'.__('Back To Index').'</a></strong>';
}
?>