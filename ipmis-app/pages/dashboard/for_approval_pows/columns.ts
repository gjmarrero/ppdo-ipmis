import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'
import DataTableDropdown from '~/components/DataTableDropDown.vue'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'
import Badge from '~/components/ui/badge/Badge.vue'

export function getBadgeVariant(status: string): 'default' | 'destructive' | 'outline' | 'secondary' {
    switch (status) {
        case 'Preengineered':
            return 'default'
        case 'UnPreengineered':
            return 'outline'
        case 'rejected':
            return 'destructive'
        default:
            return 'secondary'
    }
}

export const getForApprovalPowsColumns = ({
    onApprovePow,
}: {
    onApprovePow: (powForApproval: Project) => void,
}): ColumnDef<Project>[] => [
        {
            accessorKey: 'number',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Number', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('number')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'reference_number',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Reference Number', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('reference_number')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'title',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Title', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('title')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'approved_cost',
            header: ({ column }) => {
                return h(
                    Button,
                    {
                        variant: 'ghost',
                        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                    },
                    () => ['Cost', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]
                )
            },
            cell: ({ row }) => {
                const raw = row.getValue('approved_cost')

                const formatted = raw
                    ? new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }).format(Number(raw))
                    : ''

                return h('div', { class: 'propercase' }, formatted)
            },
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'locations',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Location', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('locations')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'date_submitted_lce',
            header: ({ column }) =>
                h(
                    'div',
                    { class: 'flex justify-center items-center' },
                    [
                        h(
                            Button,
                            {
                                variant: 'ghost',
                                class: 'flex items-center justify-center',
                                onClick: () =>
                                    column.toggleSorting(column.getIsSorted() === 'asc'),
                            },
                            () => [
                                'Date Submitted to LCE',
                                h(ArrowUpDown, { class: 'ml-2 h-4 w-4' }),
                            ]
                        ),
                    ]
                ),
            cell: ({ row }) =>
                h(
                    'div',
                    { class: 'text-center' },
                    row.getValue('date_submitted_lce')
                ),
                meta: {
                filterable: true,
            },
        },
        {
            id: 'actions',
            enableHiding: false,
            cell: ({ row }) => {
                const powForApproval = row.original

                return h('div', { class: 'relative' }, h(DataTableDropdown, {
                    powForApproval,
                    onApprovePow: () => onApprovePow(powForApproval)
                }))
            },
        },
    ]