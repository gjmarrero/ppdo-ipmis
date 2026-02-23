import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'
import DataTableDropdown from '~/components/DataTableDropDown.vue'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'
import Badge from '~/components/ui/badge/Badge.vue'
import { getBadgeVariant } from '../columns'

export function getBadgeVariant(value: string, type: 'validation_status' | 'qcp_status' | 'review_status'): string {
    if (type === 'validation_status') {
        switch (value) {
            case 'Validated':
                return 'success'
            case 'Unvalidated':
                return 'destructive'
            default:
                return 'secondary'
        }
    }

    if (type === 'qcp_status') {
        switch (value) {
            case 'QCP Reviewed':
                return 'success'
            case 'Pending':
                return 'destructive'
            default:
                return 'secondary'
        }
    }

    if (type === 'review_status') {
        switch (value) {
            case 'Recommended for Approval':
                return 'success'
            case 'Pending':
                return 'destructive'
            default:
                return 'secondary'
        }
    }
}

export const getPreEngColumns = ({
    onEditPreEng,
    onOpenDetail,
    onSiteProblem,
    onRefresh,
}: {
    onEditPreEng: (preEngineering: Project) => void,
    onOpenDetail: (preEngineering: Project) => void,
    onSiteProblem: (preEngineering: Project) => void,
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
                            variant: 'secondary',
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
        //             ? h(Badge, { variant: 'secondary', class: 'propercase items-center justify-center text-center font-homenaje', style: 'border-radius: 10px 60px 30px;' }, 'Within PA')
        //             : null
        //     },
        // },
        {
            accessorKey: 'validation_status',
            header: () => h('div', { class: 'text-left' }, 'Validation Status'),
            cell: ({ row }: any) => {
                const validation_status = row.getValue('validation_status')
                const badgeVariant = getBadgeVariant(validation_status, 'validation_status')
                return h(Badge, { variant: badgeVariant, class: 'uppercase items-center justify-center text-center', style: 'border-radius: 10px 60px 30px' }, () => validation_status)
            }
        },
        {
            accessorKey: 'latest_site_problem_type',
            header: () => h('div', { class: 'text-left' }, 'Remarks'),
            cell: ({ row }: any) => {
                const latest_site_problem_type = row.getValue('latest_site_problem_type')
                const latest_site_problem_pdc_result = row.original.latest_site_problem_pdc_result
                const latest_problem_type_status = latest_site_problem_pdc_result ? `${latest_site_problem_type} - ${latest_site_problem_pdc_result}` : latest_site_problem_type
                if (!latest_site_problem_type) return null
                return h(Badge,
                    {
                        variant: 'default',
                        class: 'text-xs px-2 py-0.5  rounded uppercase items-center justify-center text-center',
                        style: 'border-radius: 10px 30px 60px;',
                    }, () => latest_problem_type_status)
            }

        },
        {
            accessorKey: 'qcp_status',
            header: () => h('div', { class: 'text-left ' }, 'QCP Status'),
            cell: ({ row }: any) => {
                const qcp_status = row.getValue('qcp_status')
                const badgeVariant = getBadgeVariant(qcp_status, 'qcp_status')
                return h(Badge, { variant: badgeVariant, class: 'uppercase items-center justify-center text-center', style: 'border-radius: 10px 60px 30px' }, () => qcp_status)
            }
        },
        {
            accessorKey: 'review_status',
            header: () => h('div', { class: 'text-left' }, 'Status'),
            cell: ({ row }: any) => {
                const review_status = row.getValue('review_status')
                const badgeVariant = getBadgeVariant(review_status, 'review_status')
                return h(Badge, { variant: badgeVariant, class: 'uppercase items-center justify-center text-center', style: 'border-radius: 10px 60px 30px' }, () => review_status)
            }
        },
        // {
        //     accessorKey: 'preengineering_status',
        //     header: () => h('div', { class: 'text-left' }, 'Status'),
        //     cell: ({ row }: any) => {
        //         const preengineering_status = row.getValue('preengineering_status')
        //         const badgeVariant = getBadgeVariant(preengineering_status)
        //         return h(Badge, { variant: badgeVariant, class: 'uppercase items-center justify-center text-center', style: 'border-radius: 10px 60px 30px' }, () => preengineering_status)
        //     }
        // },
        // {
        //     accessorKey: 'qcp_status',
        //     header: () => h('div', {class: 'text-left '}, 'QCP Status'),
        //     cell: ({ row }: any) => {
        //         const qcp_status = row.getValue('qcp_status')
        //         const badgeVariant = getBadgeVariant(qcp_status)
        //         return h(Badge, { variant: badgeVariant, class: 'uppercase items-center justify-center text-center', style: 'border-radius: 10px 60px 30px' }, () => qcp_status)
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
                    onSiteProblem: () => onSiteProblem(project),
                    onRefresh: () => {
                        window.dispatchEvent(new CustomEvent('datatable-refresh'))
                    }
                }))
            },
        },
    ]