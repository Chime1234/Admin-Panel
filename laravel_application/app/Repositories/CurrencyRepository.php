<?php
/**
 * File name: CurrencyRepository.php
 * Last modified: 2021.01.03 at 15:29:51
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Repositories;

use App\Models\Currency;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CurrencyRepository
 * @package App\Repositories
 * @version October 22, 2019, 2:46 pm UTC
 *
 * @method Currency findWithoutFail($id, $columns = ['*'])
 * @method Currency find($id, $columns = ['*'])
 * @method Currency first($columns = ['*'])
 */
class CurrencyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'symbol',
        'code',
        'decimal_digits',
        'rounding'
    ];

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return Currency::class;
    }
}
