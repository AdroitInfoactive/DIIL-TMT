<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrderMasterAcceptedDataTable;
use App\DataTables\OrderMasterDataTable;
use App\DataTables\OrderMasterDeletedDataTable;
use App\DataTables\OrderMasterPendingDataTable;
use App\DataTables\OrderMasterRejectedDataTable;
use App\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddProductToOrderRequest;
use App\Models\Charge;
use App\Models\Client;
use App\Models\CollectionTax;
use App\Models\InvoiceEntity;
use App\Models\Product;
use App\Models\OrderCharge;
use App\Models\OrderDetail;
use App\Models\OrderMaster;
use App\Models\OrderSessionData;
use App\Models\OrderTax;
use App\Models\OrderTerm;
use App\Models\Receipt;
use App\Models\Size;
use App\Models\Tax;
use App\Models\TermsAndCondition;
use App\Models\Vendor;
use Arr;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Log;
use PDF;
use Session;
use Str;
use Validator;

class OrderController extends Controller
{
    public function index(OrderMasterDataTable $dataTable)
    {
        return $dataTable->render('admin.order.index');
    }
    public function pending(OrderMasterPendingDataTable $dataTable)
    {
        return $dataTable->render('admin.order.pending');
    }
    public function accepted(OrderMasterAcceptedDataTable $dataTable)
    {
        return $dataTable->render('admin.order.accepted');
    }
    public function rejected(OrderMasterRejectedDataTable $dataTable)
    {
        return $dataTable->render('admin.order.rejected');
    }
    public function deleted(OrderMasterDeletedDataTable $dataTable)
    {
        return $dataTable->render('admin.order.deleted');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        clearSession();
        // unset session
        // Session::forget('chargesSession_' . auth()->user()->id);
        $invoiceEntities = InvoiceEntity::where('status', 1)->get();
        $clients = Client::where('status', 1)->get(); // get all clients with status

        $products = Product::where(['status' => 1])->get();
        $sizes = Size::where(['status' => 1])->get();
        $brands = Vendor::where(['status' => 1])->get();
        $terms = TermsAndCondition::where('status', 1)->get();
        $charges = Charge::where('status', 1)->get();
        $quotationProducts = Session::get('productSession_' . auth()->user()->id);
        $quotationTerms = Session::get('termsSession_' . auth()->user()->id);
        $quotationCharges = Session::get('chargesSession_' . auth()->user()->id);
        $totalcalculations = Session::get('totalProductSession_' . auth()->user()->id);
        $make1totalTaxes = Session::get('make1totalTaxes_' . auth()->user()->id);
        // dd($quotationProducts);
        return view('admin.order.create', compact('invoiceEntities', 'clients', 'products', 'sizes', 'brands', 'quotationProducts', 'terms', 'quotationTerms', 'charges', 'quotationCharges', 'totalcalculations', 'make1totalTaxes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_entity' => ['required', 'max:255', 'exists:invoice_entities,id'],
            'organization' => ['required', 'max:500', 'exists:clients,id'],
            'prepared_by' => ['required', 'max:500', 'exists:users,name'],
            'note' => ['nullable', 'string', 'max:5000'],
        ]);
        if (!sessionExists('productSession_' . auth()->user()->id)) {
            toastr()->error('Please add product to order');
            return redirect()->back();
        }
        // storeing into master table
        $qmaster = new OrderMaster();
        $qmaster->invoice_entity_id = $request->invoice_entity;
        $qmaster->order_main_prefix = config('settings.site_prefix');
        $qmaster->order_entity_prefix = InvoiceEntity::where('id', $request->invoice_entity)->value('invoice_prefix');
        $qmaster->order_financial_year = getFinancialYear();
        $qmaster->user_id = Auth::user()->id;
        $qmaster->client_id = $request->organization;
        if (isset($request->revise) && $request->revise == 'r') {
            $rcount = OrderMaster::where(['order_financial_year' => getFinancialYear(), 'order_entity_prefix' => InvoiceEntity::where('id', $request->invoice_entity)->value('invoice_prefix'), 'order_no' => $request->quotno])->where('order_type', '!=', 'N')->count();
            $type = 'R' . ($rcount + 1);
            $qmaster->order_type = $type;
            $qmaster->order_no = $request->quotno;
        } else {
            $qmaster->order_type = "N";
            $qmaster->order_no = OrderMaster::where(['order_financial_year' => getFinancialYear(), 'order_entity_prefix' => InvoiceEntity::where('id', $request->invoice_entity)->value('invoice_prefix')])->count() + 1;
        }
        $qmaster->order_note = $request->note;
        $qmaster->order_total_quantity = Session::get('totalProductSession_' . auth()->user()->id)['total_make1_Quantity'];
        $qmaster->order_total_amount = Session::get('totalProductSession_' . auth()->user()->id)['make1priceWithTax'];
        if (isset($request->make1ttl)) {
            $qmaster->order_total_amount_withcharges = $request->make1ttl;
        } else {
            $qmaster->order_total_amount_withcharges = Session::get('totalProductSession_' . auth()->user()->id)['make1priceWithTax'];
        }

        $qmaster->tax_type = config('settings.site_inclusive_tax');
        $qmaster->po_raised_status = 'no';
        $qmaster->invoice_raised_status = 'no';
        $qmaster->order_status = 'p';

        $qmaster->save();
        // get qmaster insertion id
        $masterId = $qmaster->id;
        // insert session data
        $qsession = new OrderSessionData();
        $qsession->order_session_master_id = $masterId;
        $qsession->order_session = serialize(Session::get('productSession_' . auth()->user()->id));
        $qsession->order_terms_session = serialize(Session::get('termsSession_' . auth()->user()->id));
        $qsession->order_charges_session = serialize(Session::get('chargesSession_' . auth()->user()->id));
        $qsession->order_totalcalculations_session = serialize(Session::get('totalProductSession_' . auth()->user()->id));
        $qsession->order_make1totalTaxes_session = serialize(Session::get('make1totalTaxes_' . auth()->user()->id));
        $qsession->save();
        $quotationProducts = Session::get('productSession_' . auth()->user()->id);
        foreach ($quotationProducts as $product) {
            $qdetail = new OrderDetail();
            $qdetail->order_detail_master_id = $masterId; // order_detail_master_id
            $qdetail->product_id = $product['productData']['product'];
            $qdetail->description = $product['productData']['description'];
            $qdetail->uom_id = $product['productData']['uom'];
            $qdetail->quantity = $product['productData']['quantity'];
            $qdetail->make_id = $product['productData']['make'];
            $qdetail->price = $product['productData']['price'];
            $qdetail->priceXqty = $product['productMake1Total'];
            $qdetail->total_price = $product['productMake1TotalAmount'];
            $qdetail->status = 1;
            $qdetail->save();
            if (isset($product['productData']['taxes'])) {
                foreach ($product['productData']['taxes'] as $index => $tax) {
                    $quotationTax = new OrderTax();
                    $quotationTax->order_tax_master_id = $masterId;
                    $quotationTax->order_tax_detail_id = $qdetail->id;
                    $quotationTax->order_tax_id = $tax;
                    $quotationTax->order_tax_name = $product['productMake1Taxes'][$index]['name'];
                    $quotationTax->order_tax_value = $product['productMake1Taxes'][$index]['value'];
                    $quotationTax->order_tax_amount = $product['productMake1Taxes'][$index]['tax_amount'];
                    $quotationTax->status = 1;
                    $quotationTax->save();
                }
            }
        }
        $quotationTerms = Session::get('termsSession_' . auth()->user()->id);
        if (isset($quotationTerms)) {
            foreach ($quotationTerms as $term) {
                $quotationTerm = new OrderTerm();
                $quotationTerm->order_terms_master_id = $masterId;
                $quotationTerm->order_term_id = $term['id'];
                $quotationTerm->order_term_name = $term['name'];
                $quotationTerm->order_term_description = $term['description'];
                $quotationTerm->status = 1;
                $quotationTerm->save();
            }
        }
        $quotationCharges = Session::get('chargesSession_' . auth()->user()->id);
        if (isset($quotationCharges)) {
            foreach ($quotationCharges as $charge) {
                $quotationCharge = new OrderCharge();
                $quotationCharge->order_charge_master_id = $masterId;
                $quotationCharge->order_charge_id = $charge['id'];
                $quotationCharge->order_charge_value = $charge['value'];
                $quotationCharge->order_charge_amount = $charge['make1Calculations'];
                $quotationCharge->status = 1;
                $quotationCharge->save();
            }
        }
        // -------------------- insert into receipts table --------------------
        $receipt = new Receipt();
        $receipt->client_id = $request->organization;
        $receipt->received_date = now();
        if (isset($request->make1ttl)) {
            $receipt->ordered_amount = $request->make1ttl;
        } else {
            $receipt->ordered_amount = Session::get('totalProductSession_' . auth()->user()->id)['make1priceWithTax'];
        }
        $receipt->transaction_type = "order";
        $receipt->transaction_reference = $masterId;
        $receipt->description = "Receipt raised towards order";
        $receipt->save();
        // send email
        // unset all sesison after creating order
        Session::forget('termsSession_' . auth()->user()->id);
        Session::forget('chargesSession_' . auth()->user()->id);
        Session::forget('totalProductSession_' . auth()->user()->id);
        Session::forget('productSession_' . auth()->user()->id);
        Session::forget('make1totalTaxes_' . auth()->user()->id);
        toastr()->success('Order created successfully.');
        return to_route('order.show', $masterId);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $qmaster = OrderMaster::findOrFail($id);
        $qdetails = OrderDetail::where('order_detail_master_id', $id)->get();
        $qterms = OrderTerm::where('order_terms_master_id', $id)->get();
        $qcharges = OrderCharge::where('order_charge_master_id', $id)->get();
        $qtaxes = OrderTax::where('order_tax_master_id', $id)->get();
        return view('admin.order.view', compact('qmaster', 'qdetails', 'qterms', 'qcharges', 'qtaxes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $qmaster = OrderMaster::findOrFail($id);
        $qdetails = OrderDetail::where('order_detail_master_id', $id)->get();
        $qterms = OrderTerm::where('order_terms_master_id', $id)->get();
        $qcharges = OrderCharge::where('order_charge_master_id', $id)->get();
        $qtaxes = OrderTax::where('order_tax_master_id', $id)->get();
        $qsession = OrderSessionData::where('order_session_master_id', $id)->first();
        $invoiceEntities = InvoiceEntity::where('status', 1)->get();
        $clients = Client::where('status', 1)->get();
        $products = Product::where(['status' => 1])->get();
        $sizes = Size::where(['status' => 1])->get();
        $brands = Vendor::where(['status' => 1])->get();
        $terms = TermsAndCondition::where('status', 1)->get();
        $charges = Charge::where('status', 1)->get();
        $sessionData1 = unserialize($qsession->order_session);
        $sessionData2 = unserialize($qsession->order_terms_session);
        $sessionData3 = unserialize($qsession->order_charges_session);
        $sessionData4 = unserialize($qsession->order_totalcalculations_session);
        $sessionData5 = unserialize($qsession->order_make1totalTaxes_session);

        Session::put('productSession_' . $qmaster->user_id, !empty($sessionData1) ? $sessionData1 : []);
        Session::put('termsSession_' . $qmaster->user_id, !empty($sessionData2) ? $sessionData2 : []);
        Session::put('chargesSession_' . $qmaster->user_id, !empty($sessionData3) ? $sessionData3 : []);
        Session::put('totalProductSession_' . $qmaster->user_id, !empty($sessionData4) ? $sessionData4 : []);
        Session::put('make1totalTaxes_' . $qmaster->user_id, !empty($sessionData5) ? $sessionData5 : []);


        $quotationProducts = Session::get('productSession_' . $qmaster->user_id);
        $quotationTerms = Session::get('termsSession_' . $qmaster->user_id);
        $quotationCharges = Session::get('chargesSession_' . $qmaster->user_id);
        $totalcalculations = Session::get('totalProductSession_' . $qmaster->user_id);
        $make1totalTaxes = Session::get('make1totalTaxes_' . $qmaster->user_id);
        return view(
            'admin.order.edit',
            compact(
                'quotationProducts',
                'quotationTerms',
                'quotationCharges',
                'totalcalculations',
                'make1totalTaxes',
                'qmaster',
                'qdetails',
                'qterms',
                'qcharges',
                'qtaxes',
                'invoiceEntities',
                'clients',
                'products',
                'sizes',
                'brands',
                'terms',
                'charges'
            )
        );
    }

    public function reviseOrder(string $id)
    {
        $qmaster = OrderMaster::findOrFail($id);
        $qdetails = OrderDetail::where('quotation_detail_master_id', $id)->get();
        $qterms = OrderTerm::where('quotation_terms_master_id', $id)->get();
        $qcharges = OrderCharge::where('quotation_charge_master_id', $id)->get();
        $qtaxes = OrderTax::where('quotation_tax_master_id', $id)->get();
        $qsession = OrderSessionData::where('quotation_session_master_id', $id)->first();
        $invoiceEntities = InvoiceEntity::where('status', 1)->get();
        $clients = Client::where('status', 1)->get();
        $products = Product::where(['status' => 1])->get();
        $sizes = Size::where(['status' => 1])->get();
        $brands = Vendor::where(['status' => 1])->get();
        $terms = TermsAndCondition::where('status', 1)->get();
        $charges = Charge::where('status', 1)->get();
        $sessionData1 = unserialize($qsession->quotation_session);
        $sessionData2 = unserialize($qsession->quotation_terms_session);
        $sessionData3 = unserialize($qsession->quotation_charges_session);
        $sessionData4 = unserialize($qsession->quotation_totalcalculations_session);
        $sessionData5 = unserialize($qsession->quotation_make1totalTaxes_session);
        $sessionData6 = unserialize($qsession->quotation_make2totalTaxes_session);

        Session::put('productSession_' . $qmaster->user_id, !empty($sessionData1) ? $sessionData1 : []);
        Session::put('termsSession_' . $qmaster->user_id, !empty($sessionData2) ? $sessionData2 : []);
        Session::put('chargesSession_' . $qmaster->user_id, !empty($sessionData3) ? $sessionData3 : []);
        Session::put('totalProductSession_' . $qmaster->user_id, !empty($sessionData4) ? $sessionData4 : []);
        Session::put('make1totalTaxes_' . $qmaster->user_id, !empty($sessionData5) ? $sessionData5 : []);
        Session::put('make2totalTaxes_' . $qmaster->user_id, !empty($sessionData6) ? $sessionData6 : []);


        $quotationProducts = Session::get('productSession_' . $qmaster->user_id);
        $quotationTerms = Session::get('termsSession_' . $qmaster->user_id);
        $quotationCharges = Session::get('chargesSession_' . $qmaster->user_id);
        $totalcalculations = Session::get('totalProductSession_' . $qmaster->user_id);
        $make1totalTaxes = Session::get('make1totalTaxes_' . $qmaster->user_id);
        $make2totalTaxes = Session::get('make2totalTaxes_' . $qmaster->user_id);
        return view('admin.order.revise', compact('quotationProducts', 'quotationTerms', 'quotationCharges', 'totalcalculations', 'make1totalTaxes', 'make2totalTaxes', 'qmaster', 'qdetails', 'qterms', 'qcharges', 'qtaxes', 'invoiceEntities', 'clients', 'products', 'sizes', 'brands', 'terms', 'charges'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'invoice_entity' => ['required', 'max:255', 'exists:invoice_entities,id'],
            'organization' => ['required', 'max:500', 'exists:clients,id'],
            'prepared_by' => ['required', 'max:500', 'exists:users,name'],
            'note' => ['nullable', 'string', 'max:5000'],
        ]);
        $qmaster = OrderMaster::findOrFail($id);
        $qmaster->invoice_entity_id = $request->invoice_entity;
        $qmaster->order_main_prefix = config('settings.site_prefix');
        $qmaster->order_entity_prefix = InvoiceEntity::where('id', $request->invoice_entity)->value('invoice_prefix');
        $qmaster->order_financial_year = getFinancialYear();
        $qmaster->user_id = Auth::user()->id;
        $qmaster->client_id = $request->organization;
        $qmaster->order_note = $request->note;
        $qmaster->order_total_quantity = Session::get('totalProductSession_' . auth()->user()->id)['total_make1_Quantity'];
        $qmaster->order_total_amount = Session::get('totalProductSession_' . auth()->user()->id)['make1priceWithTax'];
        if (isset($request->make1ttl)) {
            $qmaster->order_total_amount_withcharges = $request->make1ttl;
        } else {
            $qmaster->order_total_amount_withcharges = Session::get('totalProductSession_' .
                auth()->user()->id)['make1priceWithTax'];
        }
        $qmaster->tax_type = config('settings.site_inclusive_tax');
        $qmaster->po_raised_status = 'no';
        $qmaster->invoice_raised_status = 'no';
        $qmaster->order_delete_status = 'n';
        $qmaster->order_status = 'p';
        $qmaster->save();
        $qsession = OrderSessionData::where('order_session_master_id', $id)->first();
        $qsession->order_session = serialize(Session::get('productSession_' . auth()->user()->id));
        $qsession->order_terms_session = serialize(Session::get('termsSession_' . auth()->user()->id));
        $qsession->order_charges_session = serialize(Session::get('chargesSession_' . auth()->user()->id));
        $qsession->order_totalcalculations_session = serialize(Session::get('totalProductSession_' . auth()->user()->id));
        $qsession->order_make1totalTaxes_session = serialize(Session::get('make1totalTaxes_' . auth()->user()->id));
        $qsession->save();
        OrderDetail::where('order_detail_master_id', $id)->delete();
        OrderTax::where('order_tax_master_id', $id)->delete();
        $quotationProducts = Session::get('productSession_' . auth()->user()->id);
        foreach ($quotationProducts as $product) {
            $qdetail = new OrderDetail();
            $qdetail->order_detail_master_id = $id; // order_detail_master_id
            $qdetail->product_id = $product['productData']['product'];
            $qdetail->description = $product['productData']['description'];
            $qdetail->uom_id = $product['productData']['uom'];
            $qdetail->quantity = $product['productData']['quantity'];
            $qdetail->make_id = $product['productData']['make'];
            $qdetail->price = $product['productData']['price'];
            $qdetail->priceXqty = $product['productMake1Total'];
            $qdetail->total_price = $product['productMake1TotalAmount'];
            $qdetail->status = 1;
            $qdetail->save();
            if (isset($product['productData']['taxes'])) {
                foreach ($product['productData']['taxes'] as $index => $tax) {
                    $quotationTax = new OrderTax();
                    $quotationTax->order_tax_master_id = $id;
                    $quotationTax->order_tax_detail_id = $qdetail->id;
                    $quotationTax->order_tax_id = $tax;
                    $quotationTax->order_tax_name = $product['productMake1Taxes'][$index]['name'];
                    $quotationTax->order_tax_value = $product['productMake1Taxes'][$index]['value'];
                    $quotationTax->order_tax_amount = $product['productMake1Taxes'][$index]['tax_amount'];
                    $quotationTax->status = 1;
                    $quotationTax->save();
                }
            }
        }
        OrderTerm::where('order_terms_master_id', $id)->delete();
        $quotationTerms = Session::get('termsSession_' . auth()->user()->id);
        if (isset($quotationTerms)) {
            foreach ($quotationTerms as $term) {
                $quotationTerm = new OrderTerm();
                $quotationTerm->order_terms_master_id = $id;
                $quotationTerm->order_term_id = $term['id'];
                $quotationTerm->order_term_name = $term['name'];
                $quotationTerm->order_term_description = $term['description'];
                $quotationTerm->status = 1;
                $quotationTerm->save();
            }
        }
        OrderCharge::where('order_charge_master_id', $id)->delete();
        $quotationCharges = Session::get('chargesSession_' . auth()->user()->id);
        if (isset($quotationCharges)) {
            foreach ($quotationCharges as $charge) {
                $quotationCharge = new OrderCharge();
                $quotationCharge->order_charge_master_id = $id;
                $quotationCharge->order_charge_id = $charge['id'];
                $quotationCharge->order_charge_value = $charge['value'];
                $quotationCharge->order_charge_amount = $charge['make1Calculations'];
                $quotationCharge->status = 1;
                $quotationCharge->save();
            }
        }
        // ------------------------ update receipt amount ------------------------------
        $receipt = Receipt::where('transaction_reference', $id)->first();
        if (isset($request->make1ttl)) {
            $receipt->ordered_amount = $request->make1ttl;
        } else {
            $receipt->ordered_amount = Session::get('totalProductSession_' . auth()->user()->id)['make1priceWithTax'];
        }
        $receipt->save();
        // send email
        // unset all sesison after creating order
        Session::forget('termsSession_' . auth()->user()->id);
        Session::forget('chargesSession_' . auth()->user()->id);
        Session::forget('totalProductSession_' . auth()->user()->id);
        Session::forget('productSession_' . auth()->user()->id);
        Session::forget('make1totalTaxes_' . auth()->user()->id);

        toastr()->success('Order Updated successfully.');
        return to_route('order.show', $id);
    }
    public function statusUpdate(Request $request)
    {
        $quotationMaster = OrderMaster::findOrFail($request->id);
        $quotationMaster->quotation_status = $request->status;
        $quotationMaster->save();
        // send email
        return response(['status' => "success", 'message' => 'Order Status Updated Successfully.']);
    }

    public function printOrder(string $id)
    {
        $qmaster = OrderMaster::findOrFail($id);
        $qdetails = OrderDetail::where('quotation_detail_master_id', $id)->get();
        $qterms = OrderTerm::where('quotation_terms_master_id', $id)->get();
        $qcharges = OrderCharge::where('quotation_charge_master_id', $id)->get();
        $qtaxes = OrderTax::where('quotation_tax_master_id', $id)->get();
        return view('admin.order.print', compact('qmaster', 'qdetails', 'qterms', 'qcharges', 'qtaxes'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteOrder(string $id)
    {
        $quotationMaster = OrderMaster::findOrFail($id);
        // update status
        $quotationMaster->quotation_delete_status = 'y';
        $quotationMaster->save();
        return response(['status' => "success", 'message' => 'Order deleted successfully.']);
    }
    public function removeProducts(string $id)
    {
        try {
            // Assuming you have a session variable named 'Order_session'
            $quotationSession = session('productSession_' . auth()->user()->id, []);
            foreach ($quotationSession as $key => $item) {
                if ($item['quoteProdId'] == $id) {
                    // Remove the item from the session
                    unset($quotationSession[$key]);
                    session(['productSession_' . auth()->user()->id => $quotationSession]);
                    $totalcal = calculateTotals();
                    if ($totalcal) {
                        $totalcalculations = session('totalProductSession_' . auth()->user()->id, []);
                    }
                    $quotationCharges = Session::get('chargesSession_' . auth()->user()->id);
                    if ($quotationCharges != null) {
                        $up_charge_ids = [];
                        $up_selct_value = [];
                        foreach ($quotationCharges as $key => $value) {
                            $up_charge_ids[$key] = $value['id'];
                            $up_selct_value[$value['id']] = $value['value'];
                        }
                        $updtcharge = updateCharges($up_charge_ids, $up_selct_value);
                    }
                    // Check if there are total calculations
                    $totalcalculations = isset($totalcalculations) ? $totalcalculations : [];

                    // Check if update charges is set
                    $updtcharge = isset($updtcharge) ? $updtcharge : '';

                    // Render the view
                    $view = View::make('admin.order.product-table', ['quotationProducts' => $quotationSession, 'totalcalculations' => $totalcalculations, 'quotationCharges' => $quotationCharges])->render();
                    // $view .= "<--||-->" . $updtcharge;
                    $quotationSession_after_remove = session('productSession_' . auth()->user()->id, []);
                    // dd($quotationSession_after_remove);
                    if (empty($quotationSession_after_remove)) {
                        // Handle cases where the session is empty
                        Session::forget('termsSession_' . auth()->user()->id);
                        Session::forget('chargesSession_' . auth()->user()->id);
                        Session::forget('totalProductSession_' . auth()->user()->id);
                        Session::forget('productSession_' . auth()->user()->id);
                        Session::forget('make1totalTaxes_' . auth()->user()->id);
                        Session::forget('make2totalTaxes_' . auth()->user()->id);
                    }
                    return response()->json($view);
                }
            }

            // If the item with the given ID is not found
            return response(['status' => 'error', 'message' => 'Item not found!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }

    public function getTaxes(string $id)
    {
        $tax_id = Product::where(['id' => $id, 'status' => 1])->get();
        // $tax = Tax::where(['id' => $tax_id[0]['tax_id'], 'status' => 1])->get();
        $collectionTaxes = CollectionTax::where('tax_id', $tax_id[0]['tax_id'])->get();
        if ($collectionTaxes === null) {
            return response()->json(['error' => 'Tax not found'], 404);
        }
        return response()->json($collectionTaxes);
    }
    function addProducts(AddProductToOrderRequest $request)
    {
        $tax_type = config('settings.site_inclusive_tax');
        $make1_quantity = $request->quantity;
        $tax = $request->taxes;
        $quotationSession = session('productSession_' . auth()->user()->id, []);
        // Usage for Make 1
        $make1Calculations = calculateMakeAmount($make1_quantity, $request->price, $tax, $tax_type);
        $totalMake1Amount = $make1Calculations['totalAmount'];
        $totalmake1Tax = $make1Calculations['totalTax'];
        $totalMake1AmountWithTax = $make1Calculations['totalAmountWithTax'];
        $make1tax_calculation = $make1Calculations['taxCalculation'];

        // Store the updated array back in the session
        // ------------------------------ updating product ------------------------------------------------------
        if ($request->sessionId) {
            $quoteProdId = $request->sessionId;
            // Find the product in the session data based on the provided ID
            foreach ($quotationSession as $key => $item) {

                if ($item['quoteProdId'] == $quoteProdId) {
                    $quotationSession[$key]['productData'] = $request->validated();
                    $quotationSession[$key]['productMake1Total'] = $totalMake1Amount;
                    $quotationSession[$key]['productMake1Taxes'] = $make1tax_calculation;
                    $quotationSession[$key]['productMake1TotalTax'] = $totalmake1Tax;
                    $quotationSession[$key]['productMake1TotalAmount'] = $totalMake1AmountWithTax;

                    // Save the updated session data
                    break; // Break the loop once the item is found and updated
                }
            }
        }
        // --------------------------- ading new product ----------------------------------------------------------
        else {
            $newDataArray = [
                'quoteProdId' => Str::uuid(),
                'productData' => $request->validated(),
                // -------------------------- make1 -------------------
                'productMake1Total' => $totalMake1Amount,
                'productMake1Taxes' => $make1tax_calculation,
                'productMake1TotalTax' => $totalmake1Tax,
                'productMake1TotalAmount' => $totalMake1AmountWithTax,
            ];

            $quotationSession[] = $newDataArray;
        }
        session(['productSession_' . auth()->user()->id => $quotationSession]);
        $totalcal = calculateTotals();
        if ($totalcal) {
            $totalcalculations = session('totalProductSession_' . auth()->user()->id, []);
        }
        $quotationCharges = Session::get('chargesSession_' . auth()->user()->id);
        if ($quotationCharges != null) {
            $up_charge_ids = [];
            $up_selct_value = [];
            foreach ($quotationCharges as $key => $value) {
                $up_charge_ids[$key] = $value['id'];
                $up_selct_value[$value['id']] = $value['value'];
            }
            $updtcharge = updateCharges($up_charge_ids, $up_selct_value);
        }
        $view = View::make('admin.order.product-table', ['quotationProducts' => $quotationSession, 'totalcalculations' => $totalcalculations, 'quotationCharges' => $quotationCharges])->render();
        /* if ($quotationCharges != null) {
            $view .= "<--||-->" . $updtcharge;
        } */

        return response()->json($view);
        // return response()->json($view, $updtcharge);
    }
    public function getProducts(string $id)
    {
        $quotationSession = session('productSession_' . auth()->user()->id, []);
        // Find the product in the session data based on the provided ID
        $productData = collect($quotationSession)->first(function ($product) use ($id) {
            return $product['quoteProdId'] == $id;
        });
        if ($productData) {
            return response()->json($productData);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
    // -----------------------Terms and conditions--------------------
    function addTerms(Request $request)
    {
        $terms_ids = $request->terms;
        $quotationTerms = session('termsSession_' . auth()->user()->id, []);

        // Remove terms from the session
        $quotationTerms = collect($quotationTerms)->reject(function ($term) use ($terms_ids) {
            return !in_array($term['id'], $terms_ids);
        })->all();

        // Add new terms to the session
        foreach ($terms_ids as $term_id) {
            // Check if the term with the same ID already exists in the session
            $existingTerm = Arr::first($quotationTerms, function ($term) use ($term_id) {
                return $term['id'] == $term_id;
            });

            // If the term with the same ID doesn't exist, add it to the session
            if (!$existingTerm) {
                $term = TermsAndCondition::find($term_id);

                if ($term) {
                    $quotationTerms[] = [
                        'id' => $term->id,
                        'name' => $term->name,
                        'description' => $term->description,
                    ];
                }
            }
        }

        // Change this line to use $terms instead of $request->validated()
        session(['termsSession_' . auth()->user()->id => $quotationTerms]);

        $view = View::make('admin.order.terms-table', ['quotationTerms' => $quotationTerms])->render();
        return response()->json($view);
    }
    // -----------------------Charges----------------------
    function addCharges(Request $request)
    {
        $charge_ids = $request->charges;
        $selct_value = $request->value;
        $view = updateCharges($charge_ids, $selct_value);
        return response()->json($view);
    }

    // -----------------------get-client-details----------------------
    function getClientDetails(string $id)
    {
        $client = Client::find($id);
        if ($client) {
            return response()->json($client);
        } else {
            return response()->json(['error' => 'Client Details not found'], 404);
        }
    }
}
