<?php

namespace App\Tests;

use App\Controller\ProductController;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testComputeTVA()
    {
        $product = new ProductController('Un produit', "food", 20);

        return $this->assertSame(1.1, $product->computeTVA());
    }
}
