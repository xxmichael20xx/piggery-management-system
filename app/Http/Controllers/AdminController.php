<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\Log;
use App\Models\Order;
use App\Models\Pig;
use App\Models\Quarantine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard( Request $request ) {
        $pastSevenDays = Carbon::now()->subDays(7);
        $totalPigs = Pig::count();
        $totalPigsSevenDays = Pig::where( 'created_at', '>=', $pastSevenDays )
            ->where( 'status', '!=', 'quarantined' )
            ->where( 'status', '!=', 'pending_orders' )
            ->where( 'status', '!=', 'sold' )
            ->paginate( 10 );
        $totalQuarantines = Quarantine::count();
        $totalBreeds = Breed::count();
        $totalSales = Order::where('status', 'sold')->sum('amount');

        return view('admin.dashboard', compact('totalPigs', 'totalPigsSevenDays', 'totalQuarantines', 'totalBreeds', 'totalSales'));
    }

    public function pigs( Request $request ) {
        $pigs = Pig::where( 'status', '!=', 'quarantined' )->latest()->paginate( 10 );
        $pigsCount = Pig::count();
        $breeds = Breed::latest()->get();
        $statuses = $this->statuses();

        return view('admin.pigs', compact('pigs', 'pigsCount', 'breeds', 'statuses'));
    }

    public function newPig( Request $request ) {
        $pigsCount = Pig::count();
        $this->validate($request, [
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'pig_no' => 'required',
            'breed_id' => 'required',
            'weight' => 'required|numeric|min:1|max:5000',
            'gender' => 'required',
            'date_arrived' => 'required',
            'notes' => 'nullable',
            'status' => 'required',
            'quarantine_remarks' => 'required_if:status,quarantined',
        ]);

        $pig_no = 'pig_no_' . $pigsCount + 1;
        $fileName = $pig_no . '_image_' . time() . '.' . $request->image->extension();
        if ( $request->image->move( public_path( 'uploads/pigs/images' ), $fileName ) ) {
            $newPig = new Pig;
            $newPig->pig_no = $pig_no;
            $newPig->breed_id = $request->breed_id;
            $newPig->weight = $request->weight;
            $newPig->weight_unit = 'kg';
            $newPig->image = $fileName;
            $newPig->gender = $request->gender;
            $newPig->date_arrived = $request->date_arrived;
            $newPig->notes = $request->notes ?? NULL;
            $newPig->status = $request->status;

            if ( $newPig->save() ) {
                if ( $request->status == 'quarantined' ) {
                    $newQuanrantine = new Quarantine;
                    $newQuanrantine->pig_id = $newPig->id;
                    $newQuanrantine->reason = $request->quarantine_remarks;
                    $newQuanrantine->save();

                    $this->newLog(
                        'New added pig',
                        'Added a new pig with a number of ' . $pig_no . ' with a status of Quarantined with a reason of: ' . $request->quarantine_remarks
                    );
                    return back()->with('new_pig.success', 'Pig has been added and moved to Quanrantine.');
                }

                $this->newLog(
                    'New added pig',
                    'Added a new pig with a number of ' . $pig_no . '.'
                );
                return back()->with('new_pig.success', 'New pig has been added.');
            }

            return back()->with('new_pig.failed', 'Failed to add new pig. Please try again.');
        }

        return back()->with('new_pig.failed', 'Failed to add new pig. Please try again.');
    }

    public function updatePig( Request $request, $id ) {
        $pig = Pig::find( $id );
        if ( ! $pig ) return back();

        if ( $pig->status == 'pending_orders' || $pig->status == 'sold' ) {
            return back()->with('update_pig.failed', "You can't update Pig details while the Pig is in the Orders List or it has been already sold!");
        }

        $this->validate($request, [
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'breed_id' => 'required',
            'weight' => 'required|numeric|min:1|max:5000',
            'gender' => 'required',
            'date_arrived' => 'required',
            'notes' => 'nullable',
            'status' => 'required',
            'quarantine_remarks' => 'required_if:status,quarantined',
        ]);

        $pig_status = $pig->status;
        $pig_no = $pig->pig_no;

        if ( $request->image ) {
            $fileName = $pig_no . '_image_' . time() . '.' . $request->image->extension();
            $fileMoved = $request->image->move( public_path( 'uploads/pigs/images' ), $fileName );
        }
        $pig->pig_no = $pig_no;
        $pig->breed_id = $request->breed_id;
        $pig->weight = $request->weight;
        $pig->weight_unit = 'kg';
        $pig->gender = $request->gender;
        $pig->date_arrived = $request->date_arrived;
        $pig->notes = $request->notes ?? NULL;
        $pig->status = $request->status;

        if ( isset( $fileMoved ) ) {
            $pig->image = $fileName;
        }

        if ( $pig->save() ) {
            $isQuanrantined = Quarantine::where( 'pig_id', $id )->first();
            if ( $request->status == 'quarantined' ) {
                if ( ! $isQuanrantined ) {
                    $newQuanrantine = new Quarantine;
                    $newQuanrantine->pig_id = $pig->id;
                    $newQuanrantine->reason = $request->quarantine_remarks;
                    $newQuanrantine->save();
                }

                $this->newLog(
                    'Updated pig data',
                    'Updated the details of the pig ' . $pig_no . ' and moved to quarantine list.'
                );
                return back()->with('update_pig.success', 'Pig details has has been updated and moved to Quarantine');
            } else {
                $message = "Pig details has been updated";
                $details = 'Updated the details of the pig ' . $pig_no;

                if ( $pig_status == 'quarantined' && $request->status !== 'quarantined' ) {
                    $message .= " and has been removed from Quarantine.";
                    $details .= ' and removed from the list of quarantine.';

                    $quarantine = Quarantine::where( 'pig_id', $id )->first();
                    if ( $quarantine ) {
                        $quarantine->delete();
                    }
                }

                $this->newLog(
                    'Updated pig data',
                    $details
                );
                return back()->with('update_pig.success', $message);
            }
        }

        return back()->with('update_pig.failed', 'Failed to update Pig details. Please try again.');
    }

    public function showPig( Request $request, $id ) {
        $pig = Pig::findOrFail( $id );
        $breeds = Breed::latest()->get();
        $statuses = $this->statuses();

        return view('admin.show_pig', compact('pig', 'breeds', 'statuses'));
    }

    public function addToOrders( Request $request, $id ) {
        $pig = Pig::find( $id );
        if ( ! $pig ) {
            return back()->with('add_to_orders.failed', 'Failed to move Pig to orders. Please try again.');
        }

        $newOrder = new Order;
        $newOrder->pig_id = $id;
        $newOrder->amount = $request->amount;
        
        if ( $newOrder->save() ) {
            $pig->status = 'pending_orders';
            $pig->save();

            $this->newLog(
                'New order',
                'Moved the ' . $pig->pig_no . ' to orders.'
            );
            return back()->with('add_to_orders.success', 'Pig has been moved to orders.');
        }

        return back()->with('add_to_orders.failed', 'Failed to move Pig to orders. Please try again.');
    }

    public function breeds( Request $request ) {
        $breeds = Breed::latest()->paginate( 10 );

        return view('admin.breeds', compact('breeds'));
    }

    public function newBreed( Request $request ) {
        $this->validate($request, [
            'name' => 'required|unique:breeds'
        ], [
            'name.unique' => 'The breed name is already taken.'
        ]);

        $newBreed = new Breed;
        $newBreed->name = $request->name;

        if ( $newBreed->save() ) {
            $this->newLog(
                'New breed',
                'Added a new breed with a name of ' . $request->name
            );
            return back()->with('add_breed.success', 'New breed has been added.');
        }

        return back()->with('add_breed.failed', 'Failed to add new breed. Please try again.');
    }

    public function updateBreed( Request $request ) {
        $breed = Breed::find( $request->update_id );

        if ( ! $breed ) {
            return back()->with('update_breed.failed', 'Failed to update breed. Please try again.');
        }
        $this->validate($request, [
            'update_name' => 'required|unique:breeds,name,' . $request->update_id . ',id'
        ], [
            'update_name.unique' => 'The breed name is already taken.'
        ]);

        $prevBreedName = $breed->name;
        $breed->name = $request->update_name;

        if ( $breed->save() ) {
            $this->newLog(
                'Breed update',
                'Updated the breed name from ' . $prevBreedName . ' to ' . $request->update_name
            );
            return back()->with('update_breed.success', 'Breed has been updated.');
        }

        return back()->with('update_breed.failed', 'Failed to update breed. Please try again.');
    }

    public function quarantine( Request $request ) {
        $quarantines = Quarantine::latest()->paginate( 10 );

        return view('admin.quarantine', compact('quarantines'));
    }

    public function restorePig( Request $request ) {
        $quarantine = Quarantine::find( $request->restore_id );
        $pig = $quarantine->pig;

        if ( ! $quarantine ) {
            return back()->with('restore_pig.failed', 'Failed to remove pig from the quarantine list!. Please try again.');
        }

        if ( $quarantine->delete() ) {
            $pig->status = 'active';
            $pig->save();

            $this->newLog(
                'Quarantin update',
                'Removed ' . $pig->pig_no . ' from the quarantine list.'
            );
            return back()->with('restore_pig.success', 'Pig has been removed from the quarantine list!');
        }

        return back()->with('restore_pig.failed', 'Failed to remove pig from the quarantine list!. Please try again.');
    }

    public function orders( Request $request, $type = 'pending_orders' ) {
        $orders = Order::where('status', $type)->latest()->paginate( 10 );

        return view('admin.orders', compact('orders', 'type'));
    }

    public function orderUpdate( Request $request ) {
        $order = Order::find( $request->action_id );
        
        if ( ! $order ) {
            $title = $request->action_type == 'cancel' ? 'Failed to cancel order.' : 'Failed to finish order.';
            return back()->with('action.failed', $title . ' Please try again.');
        }

        $pig = $order->pig;

        if ( $request->action_type == 'cancel' ) {
            $pig->status = 'active';
            $pig->save();

            $order->delete();
            $this->newLog(
                'Order update',
                'Canceled the order for the ' . $pig->pig_no
            );
            return back()->with('order.update_success', 'Pig order has been canceled!');
        } else {
            $pig->status = 'sold';
            $pig->save();

            $order->status = 'sold';
            $order->save();

            $this->newLog(
                'Order update',
                'Marked the order number ' . $request->action_id . ' as sold.'
            );
            return back()->with('order.update_success', 'Pig order has been marked has sold!');
        }
    }

    public function logs( Request $request ) {
        $logs = Log::latest()->paginate( 10 );

        return view('admin.logs', compact('logs'));
    }
}
