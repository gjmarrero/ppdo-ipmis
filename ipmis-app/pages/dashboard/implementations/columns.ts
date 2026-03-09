import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'
import DataTableDropdown from '~/components/DataTableDropDown.vue'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'
import Badge from '~/components/ui/badge/Badge.vue'

export function getBadgeVariant(status: string): 'default' | 'destructive' | 'outline' | 'secondary' {
    switch (status) {
        case 'Clearance Issued':
            return 'default'
        case 'In Progress':
            return 'outline'
        case 'No Clearance':
            return 'destructive'
        default:
            return 'secondary'
    }
}

export const getImplementationColumns = ({
    onEditPreEng,
    onOpenDetail,
    onRefresh,
}: {
    onEditPreEng: (environmentalClearance: Project) => void,
    onOpenDetail: (environmentalClearance: Project) => void,
    onRefresh: () => void,
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
        },
        {
            accessorKey: 'reference_number',
            header: ({ column }) =>
                h(
                    'div',
                    { class: 'flex justify-center w-full' },
                    h(
                        Button,
                        {
                            variant: 'ghost',
                            class: 'flex items-center justify-center text-center',
                            onClick: () =>
                                column.toggleSorting(column.getIsSorted() === 'asc'),
                        },
                        () => [
                            'Reference Number',
                            h(ArrowUpDown, { class: 'ml-2 h-4 w-4' }),
                        ]
                    )
                ),
            cell: ({ row }) =>
                h(
                    'div',
                    { class: 'flex justify-center text-center' },
                    row.getValue('reference_number')
                ),
        },
        {
            accessorKey: 'title',
            header: ({ column }) =>
                h(
                    'div',
                    { class: 'flex justify-center w-full' },
                    h(
                        Button,
                        {
                            variant: 'ghost',
                            class: 'flex items-center justify-center text-center',
                            onClick: () =>
                                column.toggleSorting(column.getIsSorted() === 'asc'),
                        },
                        () => [
                            'Title',
                            h(ArrowUpDown, { class: 'ml-2 h-4 w-4' }),
                        ]
                    )
                ),
            cell: ({ row }) =>
                h(
                    'div',
                    { class: 'flex justify-center text-center' },
                    row.getValue('title')
                ),
        },
        {
            accessorKey: 'date_approved_lce',
            header: ({ column }) =>
                h(
                    'div',
                    { class: 'flex justify-center w-full' },
                    h(
                        Button,
                        {
                            variant: 'ghost',
                            class: 'flex items-center justify-center text-center',
                            onClick: () =>
                                column.toggleSorting(column.getIsSorted() === 'asc'),
                        },
                        () => [
                            'Date POW Approved',
                            h(ArrowUpDown, { class: 'ml-2 h-4 w-4' }),
                        ]
                    )
                ),
            cell: ({ row }) =>
                h(
                    'div',
                    { class: 'flex justify-center text-center' },
                    row.getValue('date_approved_lce')
                ),
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
        },
        {
            accessorKey: 'date_documents_received',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Date Documents Received', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => {
                const value = row.getValue('date_documents_received')
                if (value === 'Pending') {
                    return h('div', { class: 'flex justify-center' },
                        h(Badge,
                            { variant: 'destructive', class: 'uppercase items-center justify-center text-center', style: 'border-radius: 10px 60px 30px' },
                            () => value
                        )
                    )
                }
                return h('div', { class: 'flex justify-center' }, value)
            }
        },
        {
            accessorKey: 'date_start_implementation',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Date Start', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => {
                const value = row.getValue('date_start_implementation')
                if (value === 'Pending') {
                    return h('div', { class: 'flex justify-center' },
                        h(Badge,
                            { variant: 'destructive', class: 'uppercase items-center justify-center text-center', style: 'border-radius: 10px 60px 30px' },
                            () => value
                        )
                    )
                }
                return h('div', { class: 'flex justify-center' }, value)
            }
        },
        // {
        //     accessorKey: 'environmental_clearance_status',
        //     header: () => h('div', { class: 'text-left' }, 'Status'),
        //     cell: ({ row }: any) => {
        //         const environmental_clearance_status = row.getValue('environmental_clearance_status')
        //         const badgeVariant = getBadgeVariant(environmental_clearance_status)
        //         return h(Badge, { variant: badgeVariant, class: 'uppercase' }, () => environmental_clearance_status)
        //     }
        // },
        {
            id: 'actions',
            enableHiding: false,
            cell: ({ row }) => {
                const project = row.original

                return h('div', { class: 'relative' }, h(DataTableDropdown, {
                    project,
                    onEditPreEng: () => onEditPreEng(project),
                    onOpenDetail: () => onOpenDetail(project),
                    onRefresh: () => {
                        window.dispatchEvent(new CustomEvent('datatable-refresh'))
                    }
                }))
            },
        },
    ]