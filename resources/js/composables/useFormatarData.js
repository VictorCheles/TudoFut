export function useFormatarData(utcDate) {
    if (!utcDate) return "Data Inválida";

    const data = new Date(utcDate);
    return data
        .toLocaleString("pt-BR", {
            timeZone: "America/Sao_Paulo",
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        })
        .replace(",", " -"); // Substitui a vírgula por um traço
}
