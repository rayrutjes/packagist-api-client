<?php

/*
 * This file is part of PackagistApi library.
 *
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PackagistApi\Adapter;

use PackagistApi\Exception\HttpException;

interface AdapterInterface
{
    /**
     * @param string $url
     *
     * @throws HttpException
     *
     * @return string
     */
    public function get($url);
}
