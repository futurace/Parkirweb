@extends('layouts.app', ['title' => 'Transaction', 'active' => 'transactions'])

@section('top-actions')
    <div style="display: flex; gap: 10px; align-items: center;">
        @foreach ($vehicleTypes as $vt)
            <button type="button" class="vehicle-type-btn" data-id="{{ $vt->id }}">
                @if (strtolower($vt->name) === 'truck/bus/other')
                    OTHER
                @else
                    {{ strtoupper($vt->name) }}
                @endif
            </button>
        @endforeach
        <a class="btn btn-pink btn-small" href="#" id="enter-vehicle-btn">＋ Enter Vehicle</a>
    </div>
@endsection

@section('content')
    <style>
        .location-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .location-card.selected {
            border-color: var(--pink) !important;
            box-shadow: 0 10px 25px rgba(241, 5, 140, 0.15);
            transform: translateY(-2px);
        }
        .vehicle-type-btn {
            background: #252d5d;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.2s ease;
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .vehicle-type-btn.selected {
            background: linear-gradient(135deg, var(--pink), var(--purple)) !important;
            box-shadow: 0 6px 12px rgba(241, 5, 140, 0.3);
        }
        .ticket-item {
            border: 1px dashed #a2abba;
            border-radius: 6px;
            background: #fffdf2;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .ticket-item:hover {
            border-color: var(--pink) !important;
            background: #fffbe6 !important;
        }
        .ticket-item.active-ticket {
            border-color: var(--pink) !important;
            border-style: solid !important;
            box-shadow: 0 6px 15px rgba(241, 5, 140, 0.12) !important;
            background: #fffbe6 !important;
        }
        .print-ticket-btn:hover {
            background: var(--pink) !important;
            color: #fff !important;
            border-color: var(--pink) !important;
        }
        
        .alert-error {
            background: #fff5f5;
            border: 1px solid #ff1212;
            color: #ff1212;
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 22px;
            font-size: 13px;
        }
        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>

    @if ($errors->any())
        <div class="alert-error">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="dashboard-grid">
        <div>
            <div class="summary-row">
                <article class="clock-card">
                    <h3 id="clock-day">Monday</h3>
                    <p id="clock-date">8 December 2025</p>
                    <div class="time" id="clock-time">10 : 42 : 12</div>
                </article>

                @foreach ($locations as $location)
                    <article class="location-card" data-id="{{ $location->id }}">
                        <div class="location-tower">♜</div>
                        <h3>{{ $location->name }}</h3>
                        <div class="capacity">
                            <span>🏍 {{ $location->max_motorcycle }}</span>
                            <span>🚘 {{ $location->max_car }}</span>
                            <span>🚚 {{ $location->max_truck_bus_other }}</span>
                        </div>
                        <div class="used">
                            <span class="{{ $location->parked_motorcycle >= $location->max_motorcycle ? 'danger' : '' }}">
                                🏍 {{ $location->parked_motorcycle }}
                            </span>
                            <span class="{{ $location->parked_car >= $location->max_car ? 'danger' : '' }}">
                                🚘 {{ $location->parked_car }}
                            </span>
                            <span class="{{ $location->parked_truck >= $location->max_truck_bus_other ? 'danger' : '' }}">
                                🚚 {{ $location->parked_truck }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="panel transaction-panel">
                <div class="transaction-head">
                    <h2 class="section-title" style="margin: 0;"><strong>Transaction</strong> Input Form</h2>
                    <a class="btn btn-dark btn-small" href="#" id="exit-vehicle-btn">＋ Exit Vehicle</a>
                </div>

                <form id="transaction-form" method="POST" action="{{ route('transactions.store') }}">
                    @csrf
                    <input type="hidden" name="location_id" id="form-location-id" value="{{ old('location_id') }}">
                    <input type="hidden" name="vehicle_type_id" id="form-vehicle-type-id" value="{{ old('vehicle_type_id') }}">

                    <div class="transaction-fields">
                        <div class="field">
                            <label for="ticket_number">Ticket Number</label>
                            <textarea id="ticket_number" name="ticket_number" autofocus>{{ old('ticket_number') }}</textarea>
                        </div>
                        <div class="field">
                            <label for="police_number">Police Number</label>
                            <textarea id="police_number" name="police_number">{{ old('police_number') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="field" style="display: flex; justify-content: flex-end; margin-top: 14px;">
                        <button type="submit" class="btn btn-pink btn-small" id="form-submit-btn" style="min-width: 140px;">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>

        <aside class="panel tickets-panel">
            <div class="tickets-head" style="margin-bottom: 15px;">
                <h2>Tickets ({{ $parkedTransactions->count() }})</h2>
                <button class="outline-btn" type="button" onclick="location.reload()">REFRESH</button>
            </div>
            
            <div class="tickets-list" style="overflow-y: auto; max-height: 480px; display: flex; flex-direction: column; gap: 10px; padding-right: 4px;">
                @forelse ($parkedTransactions as $trx)
                    <div class="ticket-item ticket-receipt" data-ticket="{{ $trx->ticket_number }}" style="padding: 16px; margin-bottom: 12px; font-family: 'Courier New', Courier, monospace; position: relative; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
                        <button type="button" class="print-ticket-btn" style="position: absolute; top: 10px; right: 10px; border: 1px solid var(--line); border-radius: 4px; background: #fff; cursor: pointer; padding: 3px 5px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" title="Print Ticket">
                            <span style="font-size: 10px; font-weight: bold; font-family: 'Inter', sans-serif;">🖨️ PRINT</span>
                        </button>
                        
                        <div class="receipt-header" style="text-align: center; border-bottom: 1px dashed #a2abba; padding-bottom: 8px; margin-bottom: 8px; width: calc(100% - 70px);">
                            <div style="font-weight: 900; font-size: 13px; letter-spacing: 0.5px; color: #111;">SIJA PARKING</div>
                            <div style="font-size: 8px; color: #666; font-weight: bold; letter-spacing: 0.5px;">TANDA MASUK KENDARAAN</div>
                        </div>

                        <div class="receipt-fields" style="margin-top: 6px; display: flex; flex-direction: column; gap: 6px;">
                            <div style="display: flex; justify-content: space-between; font-size: 11px; color: #222;">
                                <span>TICKET ID</span>
                                <span style="font-weight: bold;">#{{ $trx->ticket_number }}</span>
                            </div>

                            <div style="display: flex; justify-content: space-between; font-size: 11px; color: #222;">
                                <span>TANGGAL</span>
                                <span class="vt-date" style="font-weight: bold;">{{ $trx->entry_time->format('d/m/Y') }}</span>
                            </div>

                            <div style="display: flex; justify-content: space-between; font-size: 11px; color: #222;">
                                <span>JAM</span>
                                <span class="vt-time" style="font-weight: bold;">{{ $trx->entry_time->format('H:i') }}</span>
                            </div>

                            <div style="text-align: center; background: #222; color: #fff; padding: 6px; font-weight: 900; font-size: 16px; letter-spacing: 1px; border-radius: 4px; font-family: monospace;">
                                <span class="vt-plate">{{ $trx->plate_number }}</span>
                            </div>  
                        </div>

                    
                        <div style="display:none;">
                            <span class="vt-entry">{{ $trx->entry_time->format('d/m/Y H:i') }}</span>
                            <span class="live-duration" data-entry="{{ $trx->entry_time->toISOString() }}">0m</span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--muted); padding: 50px 10px;">
                        No parked vehicles found.
                    </div>
                @endforelse
            </div>
        </aside>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
     
            function updateClock() {
                const now = new Date();
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const months = [
                    'January', 'February', 'March', 'April', 'May', 'June', 
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];
                
                const dayName = days[now.getDay()];
                const dateNum = now.getDate();
                const monthName = months[now.getMonth()];
                const year = now.getFullYear();
                
                let hours = now.getHours();
                let minutes = now.getMinutes();
                let seconds = now.getSeconds();
                
                hours = hours < 10 ? '0' + hours : hours;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;
                
                const timeString = `${hours} : ${minutes} : ${seconds}`;
                const dateString = `${dateNum} ${monthName} ${year}`;
                
                const clockDay = document.getElementById('clock-day');
                const clockDate = document.getElementById('clock-date');
                const clockTime = document.getElementById('clock-time');
                
                if (clockDay) clockDay.textContent = dayName;
                if (clockDate) clockDate.textContent = dateString;
                if (clockTime) clockTime.textContent = timeString;
            }
            
            updateClock();
            setInterval(updateClock, 1000);

         
            const locationCards = document.querySelectorAll('.location-card');
            const formLocationInput = document.getElementById('form-location-id');

            locationCards.forEach(card => {
                card.addEventListener('click', function() {
                    locationCards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    formLocationInput.value = this.getAttribute('data-id');
                });
            });

            
            const oldLocationId = "{{ old('location_id') }}";
            if (oldLocationId) {
                const activeCard = document.querySelector(`.location-card[data-id="${oldLocationId}"]`);
                if (activeCard) activeCard.classList.add('selected');
            }

            
            const vehicleBtns = document.querySelectorAll('.vehicle-type-btn');
            const formVehicleInput = document.getElementById('form-vehicle-type-id');

            vehicleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    vehicleBtns.forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    formVehicleInput.value = this.getAttribute('data-id');
                });
            });

            
            const oldVehicleTypeId = "{{ old('vehicle_type_id') }}";
            if (oldVehicleTypeId) {
                const activeBtn = document.querySelector(`.vehicle-type-btn[data-id="${oldVehicleTypeId}"]`);
                if (activeBtn) activeBtn.classList.add('selected');
            }

            
            const enterVehicleBtn = document.getElementById('enter-vehicle-btn');
            const ticketInput = document.getElementById('ticket_number');
            const policeInput = document.getElementById('police_number');

            enterVehicleBtn.addEventListener('click', function(e) {
                e.preventDefault();

                
                if (!formLocationInput.value) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Selection Required',
                        text: 'Please click on a Location Card (e.g. Gedung A) first!',
                        confirmButtonColor: '#f1058c'
                    });
                    return;
                }

                
                if (!formVehicleInput.value) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Selection Required',
                        text: 'Please select a Vehicle Type button at the top (MOTORCYCLE, CAR, OTHER) first!',
                        confirmButtonColor: '#f1058c'
                    });
                    return;
                }

                
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                
                const generatedTicket = `TCK-${year}${month}${day}-${hours}${minutes}${seconds}`;
                ticketInput.value = generatedTicket;
                
            
                policeInput.focus();

                
                ticketInput.style.borderColor = '#eb7ddd';
            });

            
            const exitVehicleBtn = document.getElementById('exit-vehicle-btn');
            exitVehicleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const ticketNum = ticketInput.value.trim();
                if (!ticketNum) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Input Required',
                        text: 'Please input or select a Ticket Number to exit!',
                        confirmButtonColor: '#252d5d'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Confirm Exit',
                    text: `Are you sure vehicle with ticket ${ticketNum} wants to exit?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#9419c6',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, checkout!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('transaction-form');
                        form.action = "{{ route('transactions.exit') }}";
                        form.submit();
                    }
                });
            });

        
            const ticketItems = document.querySelectorAll('.ticket-item');
            ticketItems.forEach(item => {
                item.addEventListener('click', function() {
                    ticketItems.forEach(t => t.classList.remove('active-ticket'));
                    this.classList.add('active-ticket');
                    
                    const ticketNum = this.getAttribute('data-ticket');
                    ticketInput.value = ticketNum;
                    policeInput.value = ''; 
                });
            });

           
            document.querySelectorAll('.print-ticket-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const ticketItem = this.closest('.ticket-item');
                    const ticketNum = ticketItem.getAttribute('data-ticket');
                    printTicket(ticketNum);
                });
            });

            function printTicket(ticketNum) {
                const ticketEl = document.querySelector(`.ticket-item[data-ticket="${ticketNum}"]`);
                if (!ticketEl) return;
                
                // Create an iframe to print just this ticket
                const iframe = document.createElement('iframe');
                iframe.style.position = 'absolute';
                iframe.style.width = '0px';
                iframe.style.height = '0px';
                iframe.style.border = 'none';
                document.body.appendChild(iframe);
                
                const doc = iframe.contentWindow.document;
                doc.open();
                doc.write(`
                    <html>
                    <head>
                        <title>Print Ticket ${ticketNum}</title>
                        <style>
                            body {
                                margin: 0;
                                padding: 20px;
                                font-family: 'Courier New', Courier, monospace;
                                text-align: center;
                                width: 280px;
                            }
                            .ticket-receipt {
                                border: 1px dashed #000;
                                padding: 16px;
                            }
                            .receipt-header {
                                border-bottom: 1px dashed #000;
                                padding-bottom: 8px;
                                margin-bottom: 8px;
                            }
                            .plate-box {
                                background: #000;
                                color: #fff;
                                padding: 6px;
                                font-weight: 900;
                                font-size: 18px;
                                letter-spacing: 1px;
                                margin-bottom: 8px;
                                display: inline-block;
                                width: 80%;
                            }
                            .row {
                                display: flex;
                                justify-content: space-between;
                                font-size: 11px;
                                margin-bottom: 4px;
                            }
                        </style>
                    </head>
                    <body onload="window.print(); setTimeout(function() { window.frameElement.remove(); }, 100);">
                        <div class="ticket-receipt">
                            <div class="receipt-header">
                                <div style="font-weight: bold; font-size: 15px; letter-spacing: 1px;">SIJA PARKING</div>
                                <div style="font-size: 10px;">TANDA MASUK KENDARAAN</div>
                            </div>
                            <div class="row">
                                <span>TICKET:</span>
                                <span style="font-weight: bold;">#${ticketNum}</span>
                            </div>

                            <div style="margin: 0 0 10px;">
                                <div style="text-align:left; font-size: 11px; display:flex; justify-content:space-between; margin-bottom: 4px;">
                                    <span style="color:#000; font-weight:900;">TICKET ID</span>
                                    <span style="font-weight:900;">#${ticketNum}</span>
                                </div>
                                <div style="text-align:left; font-size: 11px; display:flex; justify-content:space-between; margin-bottom: 4px;">
                                    <span style="color:#000; font-weight:900;">TANGGAL</span>
                                    <span style="font-weight:900;">${ticketEl.querySelector('.vt-date')?.textContent || ''}</span>
                                </div>
                                <div style="text-align:left; font-size: 11px; display:flex; justify-content:space-between; margin-bottom: 4px;">
                                    <span style="color:#000; font-weight:900;">JAM</span>
                                    <span style="font-weight:900;">${ticketEl.querySelector('.vt-time')?.textContent || ''}</span>
                                </div>
                                <div style="text-align:center; margin-top: 8px;">
                                    <div style="background:#000; color:#fff; padding: 6px; font-weight:900; font-size: 16px; letter-spacing:1px; border-radius:4px; display:inline-block; width: 80%;">
                                        ${ticketEl.querySelector('.vt-plate')?.textContent || ''}
                                    </div>
                                </div>
                            </div>


                            <div style="font-size: 9px; margin-top: 8px; border-top: 1px dashed #000; padding-top: 8px;">
                                SIMPAN TIKET INI<br>TERIMA KASIH
                            </div>
                        </div>
                    </body>
                    </html>
                `);
                doc.close();
            }

            
            function updateLiveDurations() {
                document.querySelectorAll('.live-duration').forEach(el => {
                    const entryStr = el.getAttribute('data-entry');
                    const entryTime = new Date(entryStr);
                    const now = new Date();
                    
                    const diffMs = now - entryTime;
                    const diffMinutes = Math.ceil(diffMs / (1000 * 60)); 
                    
                    const mins = diffMinutes > 0 ? diffMinutes : 1;
                    el.textContent = `${mins}m`;
                });
            }

            updateLiveDurations();
            setInterval(updateLiveDurations, 1000);
        });
    </script>
@endsection
