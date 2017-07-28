# mantis generic imports
Copyright (c) 2017  Abourahmane Fall  <fallphenix1987@gmail.com>

Released under the [MIT license](http://opensource.org/licenses/MIT)


## Description
With mantis, it is sometimes necessary to import issues from another different system (like easyvista, redmine,..).
 To solve it, this plugin offers the possibility to connect to a middleware by Rest or Soap and to recover
The list of issues. 
The middleware is responsible to get and map all issues from others system. 
The middleware can also filter issues by request parameters of import plugin.


## Requirements
phpcurl si la connexion au middleware se fait par REST.
mantis 2.0 or higher.


## Installation

1. Download or clone a copy of https://github.com/fallphenix/mantis-generic-imports.git

2. Copy GeneriqueImport plugin (the `GeneriqueImport/` directory) into your Mantis
   installation's `plugins/` directory.

3. While logged into your Mantis installation as an administrator, go to
   'Manage' -> "Manage Plugins".

4. In the "Available Plugins" list, you'll find the "Generic Import". 

5. Click on the "Generic Import" plugin to configure it.

6. Go to "List of issues" 

## Support

Bug reports or fixes are highly encouraged, and should be directed to
the bug tracker on GitHub:

  https://github.com/fallphenix/mantis-generic-imports/issues

The latest source code can be found on GitHub:

  https://github.com/fallphenix/mantis-generic-imports



Copyright (c) 2017 Abdourahmane Fall <fallphenix1987@gmail.com>

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

