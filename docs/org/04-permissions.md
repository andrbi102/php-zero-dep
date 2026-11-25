# مجوزها (۴۲)

> نسخه مهارت: **0.0.1** | دسته‌ها: ۱۲ خواندن + ۱۴ نوشتن + ۸ تأیید + ۵ وتو + ۳ اجرا

### خواندن (12)

- `read-all`
- `read-architecture`
- `read-security`
- `read-quality`
- `read-database`
- `read-frontend`
- `read-performance`
- `read-deployment`
- `read-mcp`
- `read-rea`
- `read-crypto`
- `read-graph`
### نوشتن (14)

- `write-core`
- `write-architecture`
- `write-security`
- `write-database`
- `write-frontend`
- `write-performance`
- `write-deployment`
- `write-mcp`
- `write-rea`
- `write-crypto`
- `write-graph`
- `write-docs`
- `write-tests`
- `write-release`
### تأیید (8)

- `approve-architecture`
- `approve-security`
- `approve-quality`
- `approve-performance`
- `approve-migration`
- `approve-merge`
- `approve-release`
- `approve-mcp`
### وتو (5)

- `veto-architecture`
- `veto-security`
- `veto-quality`
- `veto-performance`
- `veto-compliance`
### اجرا (3)

- `exec-migration`
- `exec-release`
- `exec-deploy`

## ماتریس نگهدارنده هر مجوز

- `read-all` → Council Architect (T-000)، Council Security (T-001)، Council Quality (T-002)، Council Performance (T-003)، Council Compliance (T-004)، Release Manager (T-005)، Council Database Seat (T-006)، Council Crypto Seat (T-007)، Council Frontend Seat (T-008)، Council Integration Seat (T-009)، Chief Architect (T-010)، Security Engineer (T-011)، Integration Lead (T-016)، Documentation Developer (T-060)، Example Developer (T-062)، Refactor Developer (T-065)، Test Runner (T-068)، Lint Runner (T-069)، Link Checker (T-070)، Code Reviewer (T-074)، Security Auditor (T-075)، Doc Writer (T-076)، Build Repairer (T-078)
- `approve-architecture` → Council Architect (T-000)
- `veto-architecture` → Council Architect (T-000)
- `approve-migration` → Council Architect (T-000)، Council Database Seat (T-006)
- `approve-release` → Council Architect (T-000)، Release Manager (T-005)
- `approve-security` → Council Security (T-001)، Council Crypto Seat (T-007)
- `veto-security` → Council Security (T-001)
- `approve-quality` → Council Quality (T-002)، Council Frontend Seat (T-008)
- `veto-quality` → Council Quality (T-002)
- `approve-performance` → Council Performance (T-003)
- `veto-performance` → Council Performance (T-003)
- `veto-compliance` → Council Compliance (T-004)
- `write-release` → Release Manager (T-005)
- `exec-release` → Release Manager (T-005)
- `read-database` → Council Database Seat (T-006)، Database Lead (T-014)، Schema Designer (T-020)، Query Optimizer (T-021)، DB Migration Specialist (T-022)، Database Developer (T-051)، Migration Developer (T-061)
- `read-crypto` → Council Crypto Seat (T-007)، Crypto Specialist (T-017)، Crypto Implementer (T-025)، Key Custodian (T-026)، Crypto Developer (T-054)
- `read-frontend` → Council Frontend Seat (T-008)، Frontend Lead (T-015)، Template Engine Specialist (T-032)، Forms & Validation UI Specialist (T-033)، Accessibility Specialist (T-034)، Asset Pipeline Specialist (T-035)، View Developer (T-052)، Forms Developer (T-055)، Frontend Developer (T-064)
- `read-mcp` → Council Integration Seat (T-009)، Integration Lead (T-016)، MCP Integration Specialist (T-045)، MCP Tool Developer (T-063)
- `approve-mcp` → Council Integration Seat (T-009)
- `write-architecture` → Chief Architect (T-010)، Routing Specialist (T-019)، Event Sourcing Specialist (T-040)، CQRS Specialist (T-041)، Event Dispatcher Specialist (T-042)
- `write-docs` → Chief Architect (T-010)، API Designer (T-018)، Tooling Specialist (T-046)، Documentation Developer (T-060)، Example Developer (T-062)، File Creator (T-066)، File Modifier (T-067)، Changelog Updater (T-071)، Tag Creator (T-072)، Logger (T-073)، Doc Writer (T-076)
- `read-security` → Security Engineer (T-011)، Session Specialist (T-023)، Auth Specialist (T-024)، Input Validation Specialist (T-027)، Output Escaping Specialist (T-028)، CSRF Specialist (T-029)، Headers & CSP Specialist (T-030)، Upload Security Specialist (T-031)، Session Developer (T-053)، Security Auditor (T-075)
- `write-security` → Security Engineer (T-011)، Session Specialist (T-023)، Auth Specialist (T-024)، Input Validation Specialist (T-027)، CSRF Specialist (T-029)، Headers & CSP Specialist (T-030)، Upload Security Specialist (T-031)، Session Developer (T-053)، Forms Developer (T-055)
- `read-quality` → QA Lead (T-012)، Tooling Specialist (T-046)، Test Engineer (T-059)، Test Writer (T-077)
- `write-tests` → QA Lead (T-012)، Test Engineer (T-059)، Test Writer (T-077)
- `read-performance` → Performance Lead (T-013)، Query Optimizer (T-021)، Cache Specialist (T-036)، OPcache Tuner (T-037)، Profiling Specialist (T-038)، Memory Optimizer (T-039)
- `write-performance` → Performance Lead (T-013)، Cache Specialist (T-036)، OPcache Tuner (T-037)، Profiling Specialist (T-038)، Memory Optimizer (T-039)
- `write-database` → Database Lead (T-014)، Schema Designer (T-020)، DB Migration Specialist (T-022)، Database Developer (T-051)، Migration Developer (T-061)
- `exec-migration` → Database Lead (T-014)، DB Migration Specialist (T-022)
- `write-frontend` → Frontend Lead (T-015)، Output Escaping Specialist (T-028)، Template Engine Specialist (T-032)، Forms & Validation UI Specialist (T-033)، Accessibility Specialist (T-034)، Asset Pipeline Specialist (T-035)، View Developer (T-052)، Forms Developer (T-055)، Frontend Developer (T-064)
- `write-mcp` → Integration Lead (T-016)، MCP Integration Specialist (T-045)، MCP Tool Developer (T-063)
- `write-crypto` → Crypto Specialist (T-017)، Crypto Implementer (T-025)، Crypto Developer (T-054)
- `read-architecture` → API Designer (T-018)، Routing Specialist (T-019)، Event Sourcing Specialist (T-040)، CQRS Specialist (T-041)، Event Dispatcher Specialist (T-042)، Core Developer (T-048)، Router Developer (T-049)، DI Container Developer (T-050)، Event Infrastructure Developer (T-056)، Queue Worker Developer (T-057)، CLI Tool Developer (T-058)، File Creator (T-066)، File Modifier (T-067)، Changelog Updater (T-071)، Tag Creator (T-072)، Logger (T-073)
- `read-graph` → Graph Specialist (T-043)، Mind Map Specialist (T-044)
- `write-graph` → Graph Specialist (T-043)، Mind Map Specialist (T-044)
- `read-rea` → Tooling Specialist (T-046)
- `write-rea` → Tooling Specialist (T-046)
- `read-deployment` → Deployment Specialist (T-047)
- `write-deployment` → Deployment Specialist (T-047)
- `exec-deploy` → Deployment Specialist (T-047)
- `write-core` → Core Developer (T-048)، Router Developer (T-049)، DI Container Developer (T-050)، Database Developer (T-051)، Event Infrastructure Developer (T-056)، Queue Worker Developer (T-057)، CLI Tool Developer (T-058)، Example Developer (T-062)، Refactor Developer (T-065)، Build Repairer (T-078)
- `approve-merge` → Code Reviewer (T-074)

قواعد SoD (نقض ممنوع): 11-sod.md — به‌ویژه استثنای مستند release.

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
