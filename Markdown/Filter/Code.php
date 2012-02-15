<?php
/**
 * Copyright (C) 2011, Maxim S. Tsepkov
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once __DIR__ . '/../Filter.php';

/**
 * Translates code blocks to <pre><code>
 *
 * Rules from markdown definition:
 *
 *   *  code block is indicated by indent at least 4 spaces or 1 tab
 *   *  one level of indentation is removed from each line of the code block
 *   *  code block continues until it reaches a line that is not indented
 *   *  within a code block, ampersands (&) and angle brackets (< and >)
 *      are automatically converted into HTML entities
 *
 * @author Igor Gaponov <jiminy96@gmail.com>
 *
 */
class Markdown_Filter_Code extends Markdown_Filter
{
    public function transform($text)
    {
        $text = preg_replace_callback(
            sprintf('/(?:\n\n|\A\n?)(?P<code>(?>( {%1$d}|\t).*\n+)+)((?=^ {0,%1$d}\S)|\Z)/m', self::$_tabWidth),
            array($this, 'transformCode'), $text);
        return $text;
    }

    /**
     * Takes a signle markdown code
     * and returns its html equivalent.
     *
     * @param array
     * @return string
     */
    protected function transformCode($values) {
        $code = self::outdent($values['code']);
        $code = htmlspecialchars($code, ENT_NOQUOTES);
        $code = ltrim($code, '\n');
        $code = rtrim($code);

        return sprintf("\n\n<pre><code>%s\n</code></pre>\n\n", $code);
    }
}
