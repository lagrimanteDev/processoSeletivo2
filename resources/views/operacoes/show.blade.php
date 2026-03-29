<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center justify-between">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Operação {{ $operacao->codigo }}
			</h2>

			<a href="{{ route('operacoes.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voltar</a>
		</div>
	</x-slot>

	<div class="py-8">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
			@if (session('status'))
				<div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
					{{ session('status') }}
				</div>
			@endif

			@if ($errors->any())
				<div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
					<ul class="list-disc list-inside">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
				<h3 class="text-lg font-medium text-gray-900 mb-4">Dados da operação</h3>

				<div class="mb-6">
					<form method="POST" action="{{ route('operacoes.update-status', $operacao) }}" class="flex items-end gap-3">
						@csrf
						@method('PATCH')

						<div>
							<label class="block text-sm text-gray-700 mb-1">Alterar status</label>
							<select name="status" class="border-gray-300 rounded-md shadow-sm" @disabled(empty($nextStatuses))>
								@if (empty($nextStatuses))
									<option value="">Sem transições disponíveis</option>
								@else
									@foreach ($nextStatuses as $statusOption)
										<option value="{{ $statusOption }}">{{ $statusOption }}</option>
									@endforeach
								@endif
							</select>
						</div>

						<x-primary-button :disabled="empty($nextStatuses)">Atualizar</x-primary-button>
					</form>
				</div>

				<dl class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
					<div>
						<dt class="text-gray-500">Cliente</dt>
						<dd class="font-medium text-gray-900">{{ $operacao->cliente?->nome }} ({{ $operacao->cliente?->cpf }})</dd>
					</div>
					<div>
						<dt class="text-gray-500">Conveniada</dt>
						<dd class="font-medium text-gray-900">{{ $operacao->conveniada?->nome }} ({{ $operacao->conveniada?->codigo }})</dd>
					</div>
					<div>
						<dt class="text-gray-500">Status</dt>
						<dd class="font-medium text-gray-900">{{ $operacao->status }}</dd>
					</div>
					<div>
						<dt class="text-gray-500">Produto</dt>
						<dd class="font-medium text-gray-900">{{ $operacao->produto }}</dd>
					</div>
					<div>
						<dt class="text-gray-500">Data criação</dt>
						<dd class="font-medium text-gray-900">{{ optional($operacao->data_criacao)->format('d/m/Y') }}</dd>
					</div>
					<div>
						<dt class="text-gray-500">Data pagamento</dt>
						<dd class="font-medium text-gray-900">{{ optional($operacao->data_pagamento)->format('d/m/Y') ?? '-' }}</dd>
					</div>
					<div>
						<dt class="text-gray-500">Valor requerido</dt>
						<dd class="font-medium text-gray-900">R$ {{ number_format((float) $operacao->valor_requerido, 2, ',', '.') }}</dd>
					</div>
					<div>
						<dt class="text-gray-500">Valor desembolso</dt>
						<dd class="font-medium text-gray-900">R$ {{ number_format((float) $operacao->valor_desembolso, 2, ',', '.') }}</dd>
					</div>
					<div>
						<dt class="text-gray-500">Total juros</dt>
						<dd class="font-medium text-gray-900">R$ {{ number_format((float) $operacao->total_juros, 2, ',', '.') }}</dd>
					</div>
				</dl>
			</div>

			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
				<h3 class="text-lg font-medium text-gray-900 mb-4">Parcelas</h3>

				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200 text-sm">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-3 py-2 text-left font-semibold text-gray-700">Número</th>
								<th class="px-3 py-2 text-left font-semibold text-gray-700">Vencimento</th>
								<th class="px-3 py-2 text-left font-semibold text-gray-700">Valor</th>
								<th class="px-3 py-2 text-left font-semibold text-gray-700">Status</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-100">
							@forelse ($operacao->parcelas as $parcela)
								<tr>
									<td class="px-3 py-2">{{ $parcela->numero }}</td>
									<td class="px-3 py-2">{{ optional($parcela->data_vencimento)->format('d/m/Y') }}</td>
									<td class="px-3 py-2">R$ {{ number_format((float) $parcela->valor, 2, ',', '.') }}</td>
									<td class="px-3 py-2">{{ $parcela->status }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="4" class="px-3 py-6 text-center text-gray-500">Sem parcelas cadastradas.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>

			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
				<h3 class="text-lg font-medium text-gray-900 mb-4">Histórico de status</h3>

				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200 text-sm">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-3 py-2 text-left font-semibold text-gray-700">De</th>
								<th class="px-3 py-2 text-left font-semibold text-gray-700">Para</th>
								<th class="px-3 py-2 text-left font-semibold text-gray-700">Usuário</th>
								<th class="px-3 py-2 text-left font-semibold text-gray-700">Data</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-100">
							@forelse ($operacao->historicoStatus as $item)
								<tr>
									<td class="px-3 py-2">{{ $item->status_anterior }}</td>
									<td class="px-3 py-2">{{ $item->status_novo }}</td>
									<td class="px-3 py-2">{{ $item->usuario?->name ?? '-' }}</td>
									<td class="px-3 py-2">{{ optional($item->created_at)->format('d/m/Y H:i') }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="4" class="px-3 py-6 text-center text-gray-500">Sem alterações de status.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
