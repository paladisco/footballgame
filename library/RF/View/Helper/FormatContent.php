<?php
class RF_View_Helper_FormatContent extends Zend_View_Helper_Abstract
{
    public function formatContent($content,$tags="<a><b><strong><h1><h2><h3><h4><h5><p><em><u><table><tr><th><td><ul><li><img><br>")
    {
		return strip_tags($content,$tags);
    }
}