# Import Excel Using The Library `maatwebsite/excel` in Laravel

Install maatwebsite/excel library by composer.
```
composer require maatwebsite/excel
```

Include the service provider / facade in `config/app.php`.

```php
'providers' => [
    //...
    Maatwebsite\Excel\ExcelServiceProvider::class,
]
//...
'aliases' => [
    //...
    'Excel' => Maatwebsite\Excel\Facades\Excel::class,
]
```
If you want to overide Excel configuration, you can publish the config.

```
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

Create Controller by the following command
```
php artisan make:controller ImportExcelController
```

Create Import Classs by the following command
```
php artisan make:import UsersImport --model=User
```

Add the following routes in `routes/web.php` file.
```php
Route::get('import-excel','ImportExcelController@ImportExcelForm')->name('import-excel');
Route::post('import-excel','ImportExcelController@ImportExcel');
```

### Handle Uploaded CSV File in Your Controller

```php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Excel;

class ImportExcelController extends Controller
{
    public function ImportExcelForm(Request $request){
        return view('import-excel');
    }
    public function ImportExcel(Request $request){
        Excel::import(new UsersImport, $request->file('excel-file'));
        return redirect('import-excel')->withMessage('Excel Imported Successfully');
    }
}

```
### Now Update UsersImport Class, implement WithHeadingRow.
```php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'=>$row['name'],
            'email'=>$row['email'],
            'password'=>Hash::make($row['password']),
        ]);
    }
}
```
### Simple Bootstrap Form For Upload Excel

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Import Excel in Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
</head>
<body>
    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                Import Excel
            </div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label  class="form-label">Upload (.csv)</label>
                        <input type="file" class="form-control" name="excel-file">
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
```