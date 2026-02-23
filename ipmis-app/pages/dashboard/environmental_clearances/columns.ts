import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown, FolderCheck, Hourglass } from 'lucide-vue-next'
import DataTableDropdown from '~/components/DataTableDropDown.vue'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'
import Badge from '~/components/ui/badge/Badge.vue'

export function getBadgeVariant(environmental_clearance_status: string): 'default' | 'destructive' | 'outline' | 'secondary' | 'success' | 'primary' {
    switch (environmental_clearance_status) {
        case 'Clearance Issued':
            return 'success'
        case 'On Process':
            return 'primary'
        case 'For Application':
            return 'destructive'
        default:
            return 'primary'
    }
}

export const getEnviClearanceColumns = ({
    onEditPreEng,
    onOpenDetail,
    onRefresh,
}: {
    onEditPreEng: (environmentalClearance: Project) => void,
    onOpenDetail: (environmentalClearance: Project) => void,
    onRefresh: () => void,
}): ColumnDef<Project>[] => [
        {
            accessorKey: 'is_pow_ready',
            header: 'POW',
            cell: ({ row }) => {
                const hasPow = row.getValue('is_pow_ready')

                return h('div', { class: 'flex justify-center' }, [
                    hasPow
                        ? h(FolderCheck, { size: 18, class: 'text-green-600' })
                        : h(Hourglass, { size: 18, class: 'text-yellow-600' })
                ])
            }
        },
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
        },
        {
            accessorKey: 'other_requirements',
            header: 'Other Requirements',
            cell: ({ row }) => {
                const requirements = row.getValue('other_requirements') || []

                if (!Array.isArray(requirements) || !requirements.length)
                    return null

                const badges = requirements.map((r: any) => {
                    const label = r.requirement_type
                        .replace(/_/g, ' ')
                        .replace(/\b\w/g, c => c.toUpperCase())

                    const isIssued = r.date_issued && r.date_issued !== ''

                    const badgeClasses = [
                        'mr-1 mb-1 text-xs items-center justify-center text-center',
                        'border-radius: 10px 60px 40px font-homenaje',
                        isIssued
                            ? 'bg-badgesuccess text-badgesuccesstext'
                            : 'bg-badgedanger text-badgedangertext'
                    ]

                    return h(
                        Badge,
                        {
                            class: badgeClasses.join(' '),
                            style: 'border-radius: 10px 60px 30px;'
                        },
                        { default: () => label }
                    )
                })

                return h('div', { class: 'flex flex-wrap' }, badges)
            },
        },
        // {
        //     accessorKey: 'is_pamb',
        //     header: () => h('div', { class: 'text-left' }, 'Protected Area'),
        //     cell: ({ row }: any) => {
        //         const isPambValue = row.getValue('is_pamb')
        //         return isPambValue === 1
        //             ? h(Badge, { variant: 'secondary', class: 'propercase items-center justify-center text-center', style: 'border-radius: 10px 60px 30px;' }, 'Within PA')
        //             : null
        //     },
        // },
        {
            accessorKey: 'certificate_type',
            header: () => h('div', { class: 'text-left' }, 'Certificate Type'),
            cell: ({ row }: any) => {
                return h(Badge, { variant: 'default', class: 'uppercase items-justify items-center text-center', style: 'border-radius: 10px 60px 30px;' }, () => row.getValue('certificate_type'))
            },
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'ec_status',
            header: () => h('div', { class: 'text-left' }, 'Status'),
            cell: ({ row }: any) => {
                const environmental_clearance_status = row.getValue('ec_status')
                const badgeVariant = getBadgeVariant(environmental_clearance_status)
                return h(Badge, { variant: badgeVariant, class: 'uppercase items-justify items-center text-center', style: 'border-radius: 10px 60px 30px;' }, () => environmental_clearance_status)
            }
        },
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