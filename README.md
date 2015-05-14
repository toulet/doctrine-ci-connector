# Twig connector for Codeigniter

This library allows to use the Doctrine ORM engine in the CodeIgniter web 
framework. 


## Legals

**Licence:** This library is provided under the [GNU General Public License, 
version 3](http://opensource.org/licenses/GPL-3.0).

**Authors:** 
 - Cyrille TOULET <cyrille.toulet@linux.com>

**Please note:** This library has been principally inspired from a tutorial 
by **Joel Verhagen**.


## Usage

### Doctrine CLI

To use the doctrine CLI, go to *application** path and run:
```sh
./doctrine-cli
```


### Doctrine in application

You can use the Doctrine entity manager in your controller.

For example:
```php
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function index()
	{
		$this->load->library('doctrine');

		$user = new Entities\User;
		$user->setFirstName('Foo');
	        $user->setLastName('Bar');

		$this->doctrine->em->persist($user);
		$this->doctrine->em->flush();

		echo 'User created.';
	}
}

```


## Tips

 * I recommend you to load Doctrine one time in the configuration file 
*application/config/autoload.php*.

