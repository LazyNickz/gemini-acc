@extends('layouts.app')
@section('title', 'Exports')

@section('content')
    <div class="mb-6">
        <h3 style="font-size:18px; font-weight:700;">Export Data</h3>
        <p style="color:var(--text-muted); font-size:14px; margin-top:4px;">Download records as CSV, Excel, or PDF</p>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:20px;">
        <!-- Mother Accounts -->
        <div class="card">
            <div class="card-header">
                <h3>👩 Mother Accounts</h3>
            </div>
            <div class="card-body">
                <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">All mother accounts with seat usage
                    and expiry data.</p>
                <div class="flex gap-2" style="flex-wrap:wrap;">
                    <a href="{{ route('exports.download', ['type' => 'mothers', 'format' => 'csv']) }}"
                        class="btn btn-outline btn-sm">📄 CSV</a>
                    <a href="{{ route('exports.download', ['type' => 'mothers', 'format' => 'xlsx']) }}"
                        class="btn btn-outline btn-sm">📊 Excel</a>
                    <a href="{{ route('exports.download', ['type' => 'mothers', 'format' => 'pdf']) }}"
                        class="btn btn-outline btn-sm">📕 PDF</a>
                </div>
            </div>
        </div>

        <!-- Accounts -->
        <div class="card">
            <div class="card-header">
                <h3>📧 Accounts</h3>
            </div>
            <div class="card-body">
                <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">All accounts with buyer, mother, and
                    plan data.</p>
                <div class="flex gap-2" style="flex-wrap:wrap;">
                    <a href="{{ route('exports.download', ['type' => 'accounts', 'format' => 'csv']) }}"
                        class="btn btn-outline btn-sm">📄 CSV</a>
                    <a href="{{ route('exports.download', ['type' => 'accounts', 'format' => 'xlsx']) }}"
                        class="btn btn-outline btn-sm">📊 Excel</a>
                    <a href="{{ route('exports.download', ['type' => 'accounts', 'format' => 'pdf']) }}"
                        class="btn btn-outline btn-sm">📕 PDF</a>
                </div>
            </div>
        </div>

        <!-- Buyers -->
        <div class="card">
            <div class="card-header">
                <h3>🛒 Buyers</h3>
            </div>
            <div class="card-body">
                <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">All buyer records with Meta Ads
                    info.</p>
                <div class="flex gap-2" style="flex-wrap:wrap;">
                    <a href="{{ route('exports.download', ['type' => 'buyers', 'format' => 'csv']) }}"
                        class="btn btn-outline btn-sm">📄 CSV</a>
                    <a href="{{ route('exports.download', ['type' => 'buyers', 'format' => 'xlsx']) }}"
                        class="btn btn-outline btn-sm">📊 Excel</a>
                    <a href="{{ route('exports.download', ['type' => 'buyers', 'format' => 'pdf']) }}"
                        class="btn btn-outline btn-sm">📕 PDF</a>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="card">
            <div class="card-header">
                <h3>📦 Orders</h3>
            </div>
            <div class="card-body">
                <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">All order records with buyer and
                    account info.</p>
                <div class="flex gap-2" style="flex-wrap:wrap;">
                    <a href="{{ route('exports.download', ['type' => 'orders', 'format' => 'csv']) }}"
                        class="btn btn-outline btn-sm">📄 CSV</a>
                    <a href="{{ route('exports.download', ['type' => 'orders', 'format' => 'xlsx']) }}"
                        class="btn btn-outline btn-sm">📊 Excel</a>
                    <a href="{{ route('exports.download', ['type' => 'orders', 'format' => 'pdf']) }}"
                        class="btn btn-outline btn-sm">📕 PDF</a>
                </div>
            </div>
        </div>
    </div>
@endsection