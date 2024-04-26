<?php

function fiscal_year() {
	// Oct 1st - Sept. 30th
	return (date('m') > 9) ? date('Y') + 1 : date('Y');
}