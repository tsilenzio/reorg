<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentSearch;
use \Excel;
use \DB;

class PaymentController extends Controller
{
    /**
     * Retrieve all input so it's ready when needed
     *
     * PaymentController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // Retrieve details from client request
        $this->phrase = $request->get('phrase', '');
        $this->page = $request->get('page', 1);
        $this->limit = $request->get('limit', 20);
    }

    /**
     * Display the first 20 payments
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $payments = PaymentSearch::query()
            ->orderBy('id', 'asc')
            ->take(20)
            ->get();

        return view('payments/index', ['payments' => $payments]);
    }

    /**
     * Perform a alteration of typeahead through filtering
     *
     * @return array
     */
    public function search()
    {
        // Start building an object for pagination
        $query = PaymentSearch::search($this->phrase);

        // Obtain the number of possible records for the pagination
        $count = (clone $query)
            ->select(DB::raw('count(*) AS count'))
            ->first()
            ->count;

        // Fetch the first or next 20 results
        // TODO :: Add UI support for changing the limit number
        $payments = $query
            ->offset(($this->page - 1) * $this->limit)
            ->take($this->limit)
            ->get();

        // Neatly package the pagination and payment details together
        return [
            'pagination' => [
                'currentPage' => intval($this->page),
                'lastPage' => intval(ceil($count / $this->limit)),
            ],
            'payments' => $payments,
        ];
    }

    public function download()
    {
        Excel::create(date('Y-m-d h-i-s'), function($excel) {
            // Set the title
            $excel->setTitle('Reorg Research Case Study')
                ->setCreator('Taylor Silenzio')
                ->setCompany('Reorg Research');

            if(strlen($this->phrase) > 0)
            {
                $excel->setDescription('Filtered using: ' . $this->phrase);
            } else {
                $excel->setDescription('Not filtered');
            }

            $excel->sheet('Sheet 1', function($sheet) {
                // Start building an object for pagination
                $data = PaymentSearch::search($this->phrase)
                    ->offset(($this->page - 1) * $this->limit)
                    ->take($this->limit * 10)
                    ->get();

                $sheet->fromArray($data->toArray());
            });

        })->download('xlsx');
    }
}
