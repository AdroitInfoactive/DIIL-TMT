<?php

use App\Models\Charge;
use App\Models\CollectionTax;
use App\Models\Product;

if (!function_exists('currencyPosition')) {
    function currencyPosition($price): string
    {
        $isNegative = $price < 0;
        $decimal = (string) ($price - floor($price));
        $money = abs(floor($price)); // Take absolute value for calculation
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);

        for ($i = 0; $i < $length; $i++) {
            if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
                $delimiter .= ',';
            }
            $delimiter .= $money[$i];
        }

        $result = strrev($delimiter);

        // If decimal part exists
        if ($decimal != '0') {
            $decimal = preg_replace("/0\./i", ".", $decimal);
            $decimal = substr($decimal, 0, 3);
            $result .= $decimal;
        } else {
            // If no decimal part, append .00
            $result .= '.00';
        }

        if (config('settings.site_currency_icon_position') === 'left') {
            $result = config('settings.site_currency_icon') . $result;
        } else {
            $result .= config('settings.site_currency_icon');
        }

        /* if ($isNegative) {
            // If negative, append the negative symbol at the end
            $result = '- ' . $result;
        } */

        return $result;
    }
}


/* admin side bar active */
if (!function_exists('setSidebarActive')) {
    function setSidebarActive(array $routes)
    {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return 'active';
            }
        }
        return '';
    }
}

// -------------------------------------- Quote calculation ---------------------------------------------------
if (!function_exists('calcuateTax')) {
    function calculateTax($total, $taxes = null, $tax_type)
    {
        $taxesAfterCalculation = [];
        $individual_tax = [];

        $tax = CollectionTax::whereIn('id', $taxes)->get();
        foreach ($tax as $t) {
            if ($tax_type == '0') {

                $tax_amount = $total * ($t->value / 100);
            } else {
                $tax_amount = $total - ($total * (100 / (100 + $t->value)));
            }
            $individual_tax = [
                'name' => $t->name,
                'value' => $t->value,
                'tax_amount' => $tax_amount
            ];
            $taxesAfterCalculation[] = $individual_tax;
        }
        return $taxesAfterCalculation;
    }
}

if (!function_exists('calculateMakeAmount')) {
    function calculateMakeAmount($quantity, $price, $tax = null, $tax_type)
    {
        $totalAmount = $quantity * $price;
        if ($tax) {
            $taxCalculation = calculateTax($totalAmount, $tax, $tax_type);
        } else {
            $taxCalculation = [];
        }
        // write condition for inclusive and exclusive

        $totalTax = array_sum(array_column($taxCalculation, 'tax_amount'));
        if (config('settings.site_inclusive_tax') == 0) {
            $totalAmountWithTax = $totalAmount + $totalTax;
            $totalAmount_new = $totalAmount;
        } else {
            $totalAmountWithTax = $totalAmount;
            $totalAmount_new = $totalAmount - $totalTax;
        }

        return [
            'totalAmount' => $totalAmount_new,
            'totalTax' => $totalTax,
            'totalAmountWithTax' => $totalAmountWithTax,
            'taxCalculation' => $taxCalculation
        ];
    }
}
if (!function_exists('calculateTotals')) {
    function calculateTotals()
    {
        $quotationSession = session('productSession_' . auth()->user()->id, []);
        $total_make1_Quantity = 0;
        $make1price = 0;
        $make1totalAmount = 0;
        $make1Tax = 0;
        $make1priceWithTax = 0;
        $make1totalTaxes = [];

        foreach ($quotationSession as $product) {
            $total_make1_Quantity += $product['productData']['quantity'];  // quantity make1
            $make1price += $product['productData']['price'];  // price
            $make1totalAmount += $product['productMake1Total'];  // total = quantity * price
            if (isset($product['productMake1Taxes'])) {
                foreach ($product['productMake1Taxes'] as $tax) {
                    // Get tax details
                    $taxId = $tax['value'];
                    $taxName = $tax['name'];
                    $taxAmount = $tax['tax_amount'];
                    $compoundKey = $taxName . '-' . $taxId;
                    $make1totalTaxes[$compoundKey] = ($make1totalTaxes[$compoundKey] ?? 0) + $taxAmount;
                }
            }
            $make1Tax += $product['productMake1TotalTax'];  // tax
            $make1priceWithTax += $product['productMake1TotalAmount'];  // total = quantity * price + tax
        }

        // Update the session only if 'make2' is not set for a product
        session([
            'totalProductSession_' . auth()->user()->id => [
                'total_make1_Quantity' => $total_make1_Quantity,
                'make1price' => $make1price,
                'make1totalAmount' => $make1totalAmount,
                'make1Tax' => $make1Tax,
                'make1priceWithTax' => $make1priceWithTax
            ],
            'make1totalTaxes_' . auth()->user()->id => $make1totalTaxes,
        ]);

        return true;
    }
}

if (!function_exists('calculateMake1Charges')) {
    function calculateMake1Charges($qty1, $make1total, $make1Tax, $quotationCharges, $newChargeValue)
    {
        // dd($qty, $make1total, $make1Tax, $quotationCharges);
        // $val=$quotationCharges['selct_value'];
        $make1charges = 0;
        $quotationSession = session('productSession_' . auth()->user()->id, []);
        foreach ($quotationCharges as $charge) {
            if ($charge['calculation_type'] == 'v') { // value
                if ($charge['calculation_on'] == 'f') { // fixed
                    $make1charges = $newChargeValue;
                    break;
                }
                if ($charge['calculation_on'] == 'w') { // weight
                    $make1charges = $qty1 * $newChargeValue;
                    break;
                }
            }
            if ($charge['calculation_type'] == 'p') { // percentage

                if ($charge['calculation_on'] == 'n') { // net amount
                    $make1charges = $make1total * ($newChargeValue / 100);
                    break;
                }
                if ($charge['calculation_on'] == 'g') { // gross amount
                    $make1charges = $make1total * ($newChargeValue / 100);
                    break;
                }
                if ($charge['calculation_on'] == 't') { // tax amount
                    if ($charge['referred_tax'] == 0) {
                        $make1charges = $make1Tax * ($newChargeValue / 100);
                    } else {

                        $products = DB::table('products')->where('tax_id', $charge['referred_tax'])->get();
                        foreach ($quotationSession as $session) {
                            foreach ($products as $product) {
                                if ($session['productData']['product'] == $product->id) {
                                    $make1charges += $session['productMake1TotalTax'] * ($newChargeValue / 100);
                                    break;
                                }
                            }

                        }

                    }
                }
            }

        }
        return $make1charges;
    }
}

if (!function_exists('updateCharges')) {
    function updateCharges($charge_ids, $selct_value)
    {
        $quotationSession = session('productSession_' . auth()->user()->id, []);
        $totalcalculations = session('totalProductSession_' . auth()->user()->id, []);
        $qty1 = $totalcalculations['total_make1_Quantity']; //make 1
        $make1total = $totalcalculations['make1totalAmount'];
        $make1Tax = $totalcalculations['make1Tax'];
        $quotationCharges = session('chargesSession_' . auth()->user()->id, []);

        // Remove unchecked charges from the session
        $quotationCharges = array_filter($quotationCharges, function ($charge) use ($charge_ids) {
            return in_array($charge['id'], $charge_ids);
        });

        foreach ($charge_ids as $charge_id) {
            $charge = Charge::find($charge_id);
            $newChargeValue = $charge->editable ? ($selct_value[$charge->id] ?? $charge->value) : $charge->value;
            $make1ChargeCalculations = calculateMake1Charges($qty1, $make1total, $make1Tax, [$charge], $newChargeValue);
            $existingCharge = Arr::first($quotationCharges, function ($existingCharge) use ($charge_id) {
                return $existingCharge['id'] == $charge_id;
            });

            if ($existingCharge) {
                // Charge with the same ID exists, update its values
                $existingChargeIndex = array_search($existingCharge, $quotationCharges);
                $quotationCharges[$existingChargeIndex] = [
                    'id' => $charge->id,
                    'name' => $charge->name,
                    'description' => $charge->description,
                    'calculation_type' => $charge->calculation_type,
                    'calculation_on' => $charge->calculation_on,
                    'referred_tax' => $charge->referred_tax,
                    'editable' => $charge->editable,
                    'value' => $charge->editable ? ($selct_value[$charge->id] ?? $charge->value) : $charge->value,
                    'make1Calculations' => $make1ChargeCalculations,
                ];
            } else {
                // Charge with the same ID doesn't exist, add it to the session
                $quotationCharges[] = [
                    'id' => $charge->id,
                    'name' => $charge->name,
                    'description' => $charge->description,
                    'calculation_type' => $charge->calculation_type,
                    'calculation_on' => $charge->calculation_on,
                    'referred_tax' => $charge->referred_tax,
                    'editable' => $charge->editable,
                    'value' => $charge->editable ? ($selct_value[$charge->id] ?? $charge->value) : $charge->value,
                    'make1Calculations' => $make1ChargeCalculations,
                ];
            }
        }

        $quotationCharges = array_values($quotationCharges);
        session(['chargesSession_' . auth()->user()->id => $quotationCharges]);
        // return View::make('admin.quotation.charges-table', ['quotationCharges' => $quotationCharges])->render();

        return $view = View::make('admin.order.product-table', ['quotationProducts' => $quotationSession, 'totalcalculations' => $totalcalculations, 'quotationCharges' => $quotationCharges])->render();
        // if ($quotationCharges != null) {
        //     $view .= "<--||-->" . $updtcharge;
        // }
    }
}

// ---------------------- check if the session exists or not ------------------------------
if (!function_exists('sessionExists')) {
    function sessionExists($key)
    {
        $sessionData = Session::get($key);
        return isset($sessionData) && !empty($sessionData) && is_array($sessionData);
    }
}

// ---------------------- get financial year from April to march ------------------------------
if (!function_exists('getFinancialYear')) {
    function getFinancialYear()
    {
        $crntYr = date('y');
        $crntMnth = date('m');
        if ($crntMnth > 3) {
            $nxtYr = $crntYr + 1;
            $fnclYr = $crntYr . '-' . $nxtYr;
        } else {
            $prvsYr = $crntYr - 1;
            $fnclYr = $prvsYr . '-' . $crntYr;
        }
        return $fnclYr;
    }
}
// ---------------------- get financial year from April to march ------------------------------
if (!function_exists('getFinancialYearWithDates')) {
    function getFinancialYearWithDates()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $financialYearStart = '';
        $financialYearEnd = '';

        if ($currentMonth > 3) {
            // Financial year starts from April of current year
            $financialYearStart = $currentYear . '-04-01';
            // Financial year ends on March of next year
            $financialYearEnd = ($currentYear + 1) . '-03-31';
            $financialYearString = $currentYear . '-' . ($currentYear + 1);
        } else {
            // Financial year starts from April of previous year
            $financialYearStart = ($currentYear - 1) . '-04-01';
            // Financial year ends on March of current year
            $financialYearEnd = $currentYear . '-03-31';
            $financialYearString = ($currentYear - 1) . '-' . $currentYear;
        }

        return [
            'start_date' => $financialYearStart,
            'end_date' => $financialYearEnd,
            'financial_year' => $financialYearString
        ];
    }
}

// ---------------------- Generate quotation Number ------------------------------
if (!function_exists('generateQuoteNumber')) {
    function generateQuoteNumber($main_prefix, $entity_prefix, $fy_year, $quot_no, $quot_type)
    {
        $quotation_number = "";
        $quotation_number .= strtoupper($main_prefix);
        if ($entity_prefix != "") {
            $quotation_number .= "/" . strtoupper($entity_prefix);
        }
        $quotation_number .= "/" . $fy_year . "/" . str_pad($quot_no, 5, '0', STR_PAD_LEFT);
        if ($quot_type != "N") {
            $quotation_number .= "/" . strtoupper($quot_type);
        }
        return $quotation_number;
    }
}

if (!function_exists('clearSession')) {
    function clearSession()
    {
        Session::forget('termsSession_' . auth()->user()->id);
        Session::forget('chargesSession_' . auth()->user()->id);
        Session::forget('totalProductSession_' . auth()->user()->id);
        Session::forget('productSession_' . auth()->user()->id);
        Session::forget('make1totalTaxes_' . auth()->user()->id);
        return response(['status' => "success"]);
    }
}
